<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Researcher;
use App\Models\Research_title;
use App\Models\researcher_files;
use App\Models\ReviewerFileRemark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ReviewerSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function createProtocol(array $attributes = []): Research_title
    {
        if (!isset($attributes['researcher_id'])) {
            $user = User::factory()->create(['role' => 'researcher']);
            $researcher = Researcher::create(['user_id' => $user->id]);
            $attributes['researcher_id'] = $researcher->id;
        }

        return Research_title::create(array_merge([
            'Study_Protocol_title' => 'Test Security Protocol',
            'Research_Category' => 'Health Science',
            'Status' => 'Under Review',
            'assigned_reviewers' => json_encode([]),
        ], $attributes));
    }

    /**
     * Stage 3: Security Headers test.
     */
    public function test_security_headers_are_present_on_web_routes(): void
    {
        $response = $this->get(route('login'));

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    /**
     * Stage 2: Authentication enforcement on reviewer routes.
     */
    public function test_unauthenticated_user_cannot_access_reviewer_dashboard(): void
    {
        $response = $this->get(route('reviewer.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_access_reviewer_view_files(): void
    {
        $response = $this->get('/reviewer/view-files/1');
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_serve_files(): void
    {
        $response = $this->get('/reviewer/file-serve/1');
        $response->assertRedirect(route('login'));
    }

    /**
     * Stage 2: Role-based access control (RBAC).
     */
    public function test_non_reviewer_role_is_forbidden_from_reviewer_dashboard(): void
    {
        $researcher = User::factory()->create([
            'role' => 'researcher',
            'is_verified' => true,
        ]);

        $response = $this->actingAs($researcher)->get(route('reviewer.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Stage 2: Authorization & IDOR/BOLA - Unassigned reviewer blocked.
     */
    public function test_unassigned_reviewer_cannot_view_protocol_files(): void
    {
        $reviewer = User::factory()->create([
            'role' => 'reviewer',
            'is_verified' => true,
        ]);

        $protocol = $this->createProtocol([
            'Study_Protocol_title' => 'Sample Secure Protocol',
            'assigned_reviewers' => json_encode([]),
        ]);

        $response = $this->actingAs($reviewer)->get(route('reviewer.view_files', ['id' => $protocol->id]));
        $response->assertStatus(403);
    }

    /**
     * Stage 1: Input Boundary & Disallowed File Extension Defense.
     */
    public function test_reviewer_upload_rejects_disallowed_file_types(): void
    {
        Storage::fake('public_uploads');

        $reviewer = User::factory()->create([
            'role' => 'reviewer',
            'is_verified' => true,
        ]);

        $protocol = $this->createProtocol([
            'Study_Protocol_title' => 'Upload Defense Protocol',
            'assigned_reviewers' => json_encode([$reviewer->id]),
        ]);

        // Attempt to upload malicious php file
        $maliciousFile = UploadedFile::fake()->create('malicious.php', 100, 'application/x-php');

        $response = $this->actingAs($reviewer)->post(route('reviewer.upload', ['id' => $protocol->id]), [
            'category' => 'Evaluation Form',
            'files' => [$maliciousFile],
        ]);

        $response->assertSessionHasErrors('files.0');
    }

    /**
     * Stage 2: Path Traversal defense in file serving.
     */
    public function test_reviewer_file_serve_blocks_path_traversal_sequences(): void
    {
        $reviewer = User::factory()->create([
            'role' => 'reviewer',
            'is_verified' => true,
        ]);

        $protocol = $this->createProtocol([
            'Study_Protocol_title' => 'Traversal Defense Protocol',
            'assigned_reviewers' => json_encode([$reviewer->id]),
        ]);

        $traversalFile = researcher_files::create([
            'research_title_id' => $protocol->id,
            'filename' => 'traversal_test.pdf',
            'filepath' => '../../etc/passwd',
            'filetype' => 'pdf',
            'uploaded_by' => $reviewer->id,
            'category' => 'Reviewer Uploads - General',
            'revision_number' => 0,
        ]);

        $response = $this->actingAs($reviewer)->get(route('reviewer.serve_file', ['id' => $traversalFile->id]));
        $response->assertStatus(403);
    }

    /**
     * Stage 2: BOLA / IDOR defense in completeReview per-file remarks.
     */
    public function test_complete_review_ignores_foreign_file_remarks(): void
    {
        Storage::fake('public_uploads');

        $reviewer = User::factory()->create([
            'role' => 'reviewer',
            'is_verified' => true,
        ]);

        // Protocol A (assigned to this reviewer)
        $protocolA = $this->createProtocol([
            'Study_Protocol_title' => 'Protocol Alpha',
            'assigned_reviewers' => json_encode([$reviewer->id]),
        ]);

        // An uploaded evaluation file for Protocol A (so review can be completed)
        $evalFile = researcher_files::create([
            'research_title_id' => $protocolA->id,
            'filename' => 'eval_review.pdf',
            'filepath' => 'uploads/research_files/eval_review.pdf',
            'filetype' => 'pdf',
            'uploaded_by' => $reviewer->id,
            'category' => 'Reviewer Uploads - Evaluation',
            'revision_number' => 0,
        ]);

        // Protocol B (unrelated protocol with its own file)
        $protocolB = $this->createProtocol([
            'Study_Protocol_title' => 'Protocol Beta Foreign',
            'assigned_reviewers' => json_encode([]),
        ]);

        $foreignFile = researcher_files::create([
            'research_title_id' => $protocolB->id,
            'filename' => 'foreign_doc.pdf',
            'filepath' => 'uploads/research_files/foreign_doc.pdf',
            'filetype' => 'pdf',
            'uploaded_by' => 9999,
            'category' => 'Application Form',
            'revision_number' => 0,
        ]);

        // Attempt to submit remark on foreignFile (BOLA attack)
        $response = $this->actingAs($reviewer)->post(route('reviewer.complete_review', ['id' => $protocolA->id]), [
            'suggested_review_type' => 'Expedited Review',
            'file_remarks' => [
                $foreignFile->id => 'Malicious injected remark on foreign file',
                $evalFile->id => 'Valid remark on own file',
            ],
        ]);

        // Ensure NO remark was recorded for foreignFile
        $this->assertDatabaseMissing('reviewer_file_remarks', [
            'file_id' => $foreignFile->id,
            'remarks' => 'Malicious injected remark on foreign file',
        ]);

        // Ensure valid remark was recorded for evalFile
        $this->assertDatabaseHas('reviewer_file_remarks', [
            'file_id' => $evalFile->id,
            'remarks' => 'Valid remark on own file',
        ]);
    }
}
