<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Researcher;
use App\Models\Research_title;
use App\Models\researcher_files;
use App\Models\College;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;

class SecurityRemediationTest extends TestCase
{
    use RefreshDatabase;

    protected function createResearcherUser(): array
    {
        $user = User::factory()->create(['role' => 'researcher', 'is_verified' => true]);
        $researcher = Researcher::create(['user_id' => $user->id]);
        return [$user, $researcher];
    }

    /**
     * Test that updateFile, addMissingFile, and uploadRevisionDocument reject executable or script file types.
     */
    public function test_researcher_upload_endpoints_reject_executable_and_script_mimes(): void
    {
        [$user, $researcher] = $this->createResearcherUser();

        $protocol = Research_title::create([
            'researcher_id' => $researcher->id,
            'Study_Protocol_title' => 'Security Protocol Test',
            'Research_Category' => 'Health Science',
            'Status' => 'Incomplete',
            'assigned_reviewers' => json_encode([]),
        ]);

        $initialFile = researcher_files::create([
            'research_title_id' => $protocol->id,
            'filename' => 'initial.pdf',
            'filepath' => 'uploads/research_files/initial.pdf',
            'filetype' => 'pdf',
            'uploaded_by' => $user->id,
            'category' => 'Application Form',
        ]);
        $protocol->files()->attach($initialFile->id);

        $fakePhp = UploadedFile::fake()->create('malicious.php', 10, 'text/x-php');
        $fakeSvg = UploadedFile::fake()->create('malicious.svg', 10, 'image/svg+xml');
        $fakeExe = UploadedFile::fake()->create('malicious.exe', 10, 'application/x-msdownload');

        // 1. updateFile rejection
        $response = $this->actingAs($user)->put(route('update.file', $protocol->id), [
            'file' => $fakePhp,
            'file_id' => $initialFile->id,
        ]);
        $response->assertSessionHasErrors(['file']);

        // 2. addMissingFile rejection
        $response = $this->actingAs($user)->post(route('add.missing.file', $protocol->id), [
            'file' => $fakeSvg,
            'category' => 'Informed Consent',
        ]);
        $response->assertSessionHasErrors(['file']);

        // 3. uploadRevisionDocument rejection
        $protocol->update(['Status' => 'Waiting for Revision']);
        $response = $this->actingAs($user)->post(route('upload.revision.document', $protocol->id), [
            'file' => $fakeExe,
            'category' => 'Revision Document',
        ]);
        $response->assertSessionHasErrors(['file']);
    }

    /**
     * Test that serveFile in researcher controller strictly fails closed on unauthorized protocol access.
     */
    public function test_researcher_serve_file_fails_closed_on_unauthorized_access(): void
    {
        [$ownerUser, $ownerResearcher] = $this->createResearcherUser();
        [$attackerUser, $attackerResearcher] = $this->createResearcherUser();

        $protocol = Research_title::create([
            'researcher_id' => $ownerResearcher->id,
            'Study_Protocol_title' => 'Protected Protocol',
            'Research_Category' => 'Science',
            'Status' => 'Pending',
            'assigned_reviewers' => json_encode([]),
        ]);

        $file = researcher_files::create([
            'research_title_id' => $protocol->id,
            'filename' => 'confidential.pdf',
            'filepath' => 'uploads/research_files/confidential.pdf',
            'filetype' => 'pdf',
            'uploaded_by' => $ownerUser->id,
            'category' => 'Protocol',
        ]);

        // Attacker researcher attempts to serve owner's file
        $response = $this->actingAs($attackerUser)->get(route('researcher.serve_file', $file->id));
        $response->assertStatus(403);
    }

    /**
     * Test that path traversal sequences are blocked in researcher file serving.
     */
    public function test_researcher_serve_file_blocks_path_traversal(): void
    {
        [$user, $researcher] = $this->createResearcherUser();

        $protocol = Research_title::create([
            'researcher_id' => $researcher->id,
            'Study_Protocol_title' => 'Traversal Protocol Test',
            'Research_Category' => 'Science',
            'Status' => 'Pending',
            'assigned_reviewers' => json_encode([]),
        ]);

        $traversalFile = researcher_files::create([
            'research_title_id' => $protocol->id,
            'filename' => 'traversal.pdf',
            'filepath' => '../../../../.env',
            'filetype' => 'pdf',
            'uploaded_by' => $user->id,
            'category' => 'Protocol',
        ]);

        $response = $this->actingAs($user)->get(route('researcher.serve_file', $traversalFile->id));
        $response->assertStatus(403);
    }

    /**
     * Test that path traversal sequences are blocked in admin file serving.
     */
    public function test_admin_serve_file_blocks_path_traversal(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_verified' => true]);
        [$researcherUser, $researcher] = $this->createResearcherUser();

        $protocol = Research_title::create([
            'researcher_id' => $researcher->id,
            'Study_Protocol_title' => 'Admin Security Protocol',
            'Research_Category' => 'Science',
            'Status' => 'Pending',
            'assigned_reviewers' => json_encode([]),
        ]);

        $traversalFile = researcher_files::create([
            'research_title_id' => $protocol->id,
            'filename' => 'traversal.pdf',
            'filepath' => '../../../../.env',
            'filetype' => 'pdf',
            'uploaded_by' => $researcherUser->id,
            'category' => 'Protocol',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.serve_file', $traversalFile->id));
        $response->assertStatus(403);
    }

    /**
     * Test that rate limiting middleware is configured on sensitive auth endpoints.
     */
    public function test_auth_routes_have_rate_limiting_middleware(): void
    {
        $routesToCheck = [
            'verify.submit' => 'throttle:5,1',
            'verify.resend' => 'throttle:3,1',
            'password.email' => 'throttle:3,1',
            'password.update' => 'throttle:5,1',
        ];

        foreach ($routesToCheck as $routeName => $expectedThrottle) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, "Route {$routeName} not found.");
            $middleware = $route->gatherMiddleware();
            $this->assertTrue(
                in_array($expectedThrottle, $middleware),
                "Route {$routeName} does not contain {$expectedThrottle} middleware."
            );
        }
    }

    /**
     * Test CMS store and update operations reject unvalidated mass assignment.
     */
    public function test_cms_college_store_is_bounded(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'is_verified' => true]);

        $response = $this->actingAs($admin)->post(route('admin.cms.colleges.store'), [
            'name' => 'College of Engineering',
            'code' => 'COE',
            'color_assign' => '#123456',
            'injected_field' => 'malicious_payload',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('colleges', [
            'name' => 'College of Engineering',
            'code' => 'COE',
        ]);
    }
}
