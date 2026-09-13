<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Researcher;
use App\Models\Reviewer;
use App\Models\Meeting;
use App\Models\AgendaItem;
use App\Models\MeetingAttendee;
use App\Models\Research_title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeetingRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $reviewer1;
    private User $reviewer2;
    private User $researcherUser;
    private Researcher $researcher;
    private Research_title $protocol;
    private Meeting $meeting;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@wmsu.edu.ph',
        ]);

        // 2. Reviewer 1 (Assigned to Protocol)
        $this->reviewer1 = User::factory()->create([
            'role' => 'reviewer',
            'email' => 'reviewer1@wmsu.edu.ph',
        ]);
        Reviewer::create([
            'user_id' => $this->reviewer1->id,
            'college' => 'College of Science',
        ]);

        // 3. Reviewer 2 (Unassigned)
        $this->reviewer2 = User::factory()->create([
            'role' => 'reviewer',
            'email' => 'reviewer2@wmsu.edu.ph',
        ]);
        Reviewer::create([
            'user_id' => $this->reviewer2->id,
            'college' => 'College of Liberal Arts',
        ]);

        // 4. Researcher & Protocol
        $this->researcherUser = User::factory()->create([
            'role' => 'researcher',
            'email' => 'researcher@wmsu.edu.ph',
        ]);
        $this->researcher = Researcher::create([
            'user_id' => $this->researcherUser->id,
            'college' => 'College of Engineering',
        ]);

        $this->protocol = Research_title::create([
            'Study_Protocol_title' => 'Novel AI-Assisted Clinical Ethics Review Framework',
            'Status' => 'Panel Deliberation',
            'Research_Category' => 'Health Science',
            'research_type' => 'Faculty Research',
            'Review_Type' => 'Full Review',
            'project_type' => 'Funded Research',
            'researcher_id' => $this->researcher->id,
        ]);

        // Assign Reviewer 1 to protocol
        $this->protocol->reviewers()->attach($this->reviewer1->id, [
            'role' => 'Primary Reviewer',
            'status' => 'Pending',
        ]);

        // 5. Meeting & Agenda
        $this->meeting = Meeting::create([
            'title' => 'September Regular Full Board Deliberation',
            'type' => 'Regular',
            'meeting_date' => now()->addDays(3)->setHour(14)->setMinute(0),
            'venue' => 'Executive Conference Hall Room 302',
            'status' => 'Scheduled',
            'agenda_status' => 'Provisional',
        ]);

        AgendaItem::create([
            'meeting_id' => $this->meeting->id,
            'section' => 'New Business: Protocol Review',
            'content' => 'Full Board Ethical Review of Clinical Submissions',
            'order' => 1,
            'protocol_id' => $this->protocol->id,
        ]);
    }

    public function test_reviewer_assigned_to_protocol_sees_meeting(): void
    {
        $response = $this->actingAs($this->reviewer1)
            ->get(route('reviewer.meetings'));

        $response->assertStatus(200);
        $response->assertSee('September Regular Full Board Deliberation');
        $response->assertSee('Novel AI-Assisted Clinical Ethics Review Framework');
    }

    public function test_unassigned_and_uninvited_reviewer_does_not_see_meeting(): void
    {
        $response = $this->actingAs($this->reviewer2)
            ->get(route('reviewer.meetings'));

        $response->assertStatus(200);
        $response->assertDontSee('September Regular Full Board Deliberation');
        $response->assertSee('No Upcoming Meetings Scheduled');
    }

    public function test_invited_reviewer_sees_meeting_even_without_assigned_protocol(): void
    {
        MeetingAttendee::create([
            'meeting_id' => $this->meeting->id,
            'user_id' => $this->reviewer2->id,
            'role' => 'reviewer',
            'status' => 'Invited',
        ]);

        $response = $this->actingAs($this->reviewer2)
            ->get(route('reviewer.meetings'));

        $response->assertStatus(200);
        $response->assertSee('September Regular Full Board Deliberation');
    }

    public function test_reviewer_can_confirm_attendance(): void
    {
        $response = $this->actingAs($this->reviewer1)
            ->post(route('reviewer.meetings.attendance', $this->meeting->id), [
                'status' => 'Confirmed',
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('meeting_attendees', [
            'meeting_id' => $this->meeting->id,
            'user_id' => $this->reviewer1->id,
            'status' => 'Confirmed',
        ]);
    }

    public function test_reviewer_can_record_regrets(): void
    {
        $response = $this->actingAs($this->reviewer1)
            ->post(route('reviewer.meetings.attendance', $this->meeting->id), [
                'status' => 'Regrets',
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('meeting_attendees', [
            'meeting_id' => $this->meeting->id,
            'user_id' => $this->reviewer1->id,
            'status' => 'Regrets',
        ]);
    }

    public function test_researcher_sees_deliberation_notice_for_own_protocol(): void
    {
        $response = $this->actingAs($this->researcherUser)
            ->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Scheduled for Deliberation');
        $response->assertSee('September Regular Full Board Deliberation');
    }

    public function test_researcher_cannot_access_admin_or_reviewer_meetings(): void
    {
        $adminMeeting = $this->actingAs($this->researcherUser)
            ->get(route('admin.meetings'));
        $adminMeeting->assertStatus(403);

        $reviewerMeeting = $this->actingAs($this->researcherUser)
            ->get(route('reviewer.meetings'));
        $reviewerMeeting->assertStatus(403);
    }

    public function test_admin_can_manage_attendees(): void
    {
        $storeResponse = $this->actingAs($this->admin)
            ->post(route('admin.meetings.attendees.store', $this->meeting->id), [
                'user_id' => $this->reviewer2->id,
                'role' => 'reviewer',
                'status' => 'Invited',
            ]);

        $storeResponse->assertSessionHas('success');
        $this->assertDatabaseHas('meeting_attendees', [
            'meeting_id' => $this->meeting->id,
            'user_id' => $this->reviewer2->id,
            'status' => 'Invited',
        ]);

        $attendee = MeetingAttendee::where('meeting_id', $this->meeting->id)
            ->where('user_id', $this->reviewer2->id)
            ->first();

        $destroyResponse = $this->actingAs($this->admin)
            ->delete(route('admin.meetings.attendees.destroy', [$this->meeting->id, $attendee->id]));

        $destroyResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('meeting_attendees', [
            'id' => $attendee->id,
        ]);
    }
}
