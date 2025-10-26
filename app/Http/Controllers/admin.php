<?php

namespace App\Http\Controllers;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\SubmissionAppointed;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentMail;
use App\Notifications\AppointmentNotification;
class admin extends Controller
{
    public function viewFiles($id)
    {
        // Find the research title
        $researchTitle = Research_title::findOrFail($id);

        // Fetch files related to that research title
        $files = Research_title::where('research_id', $id)->get();

        return view('admin.view_files', compact('files', 'researchTitle'));
    }
    public function index($request)
    {
        return view('admin.analytics');
    }
    public function applications()
    {
        $data = [
            ['id' => 1, 'name' => 'John Doe',    'email' => 'john@example.com',  'status' => 'Review',     'date' => '2025-09-10', 'title' => 'Research on AI',           'ReviewType' => 'Full Review'],
            ['id' => 2, 'name' => 'Jane Roe',    'email' => 'jane@example.com',  'status' => 'Revision',   'date' => '2025-09-12', 'title' => 'Nursing Study',            'ReviewType' => 'Exempt',     'RevisionStage' => 'Waiting for Revision'],
            ['id' => 3, 'name' => 'Sam Smith',   'email' => 'sam@example.com',   'status' => 'Complete',   'date' => '2025-08-30', 'title' => 'Grassland Ecology',        'ReviewType' => 'Expedited'],
            ['id' => 4, 'name' => 'Lisa Wong',   'email' => 'lisa@example.com',  'status' => 'Finalization','date' => '2025-09-01', 'title' => 'Behavioral Study',         'ReviewType' => 'Full Review'],
            ['id' => 5, 'name' => 'Tom Brown',   'email' => 'tom@example.com',   'status' => 'Revision',   'date' => '2025-09-15', 'title' => 'Clinical Trial',           'ReviewType' => 'Full Review','RevisionStage' => 'Panel Deliberation'],
            // ['id' => 6, 'name' => 'Anna Green',  'email' => 'anna@example.com',  'status' => 'Review',     'date' => '2025-09-18', 'title' => 'AI Ethics',                'ReviewType' => 'Expedited'],
            // ['id' => 7, 'name' => 'Mark Blue',   'email' => 'mark@example.com',  'status' => 'Revision',   'date' => '2025-09-20', 'title' => 'Environmental Impact',     'ReviewType' => 'Exempt',     'RevisionStage' => 'Submission of Revsion'],
            // ['id' => 8, 'name' => 'Rita Black',  'email' => 'rita@example.com',  'status' => 'Complete',   'date' => '2025-09-05', 'title' => 'Public Health Survey',     'ReviewType' => 'Full Review'],
            // ['id' => 9, 'name' => 'Carlos Diaz', 'email' => 'carlos@example.com','status' => 'Finalization','date' => '2025-09-07', 'title' => 'Soil Microbiology',        'ReviewType' => 'Expedited'],
            // ['id' => 10,'name' => 'Maya Patel',  'email' => 'maya@example.com',  'status' => 'Revision',   'date' => '2025-09-22', 'title' => 'Nutrition Study',          'ReviewType' => 'Full Review','RevisionStage' => 'Checking of Revision'],
        ];
            
        return view('admin.applications')->with('datas', $data);
    }
    public function GetReview()
    {
                $datas = Research_title::with('author')
            ->where('Status', 'For inital Review')
            ->get();
        return view('admin.review', compact('datas'));    
    }

        public function GetRevision()
    {
                $datas = Research_title::with('author')
            ->where('Status', 'Revision')
            ->get();
        return view('admin.revisions', compact('datas'));    
    }
    public function newSubmissions()
    {
        $pendingSubmissions = Research_title::with('author')
            ->where('Status', 'Pending')
            ->get();

        $incompleteSubmissions = Research_title::with('author')
            ->where('Status', 'Incomplete')
            ->get();
    return view('admin.NewSubmissions', compact('pendingSubmissions', 'incompleteSubmissions'));
    }

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string',
        'appointment_date' => 'nullable|date'
    ]);

    $submission = Research_title::findOrFail($id);
    $submission->Status = $request->status;
    $submission->save();

    // If the admin marked as "For Initial Review"
    if ($request->status === 'For Initial Review') {
        $appointment = Appointment::create([
            'research_title_id' => $submission->id,
            'user_id' => $submission->user_id,
            'appointment_date' => $request->appointed_date,
        ]);

        // Notify the user
        $user = User::find($submission->user_id);
        if ($user) {
            Notification::send($user, new SubmissionAppointed($submission, $appointment));
        }
    }

    return response()->json(['success' => true]);
}
 public function setInitialReview(Request $request, $id)
    {
        $request->validate([
            'appointment_date' => 'required|date',
        ]);

        // Find the research title
        $research = Research_title::findOrFail($id);

        // Update status to “For Initial Review”
        $research->Status = 'For Initial Review';
        $research->save();

        // Create appointment record
        $appointment = Appointment::create([
            'research_title_id' => $research->id,
            'user_id' => $research->user_id,
            'appointment_date' => $request->appointment_date,
        ]);

        // Send email to the user
        Mail::to($research->user->email)->send(new AppointmentMail($appointment));

        // Send notification
        $research->user->notify(new AppointmentNotification($appointment));

        return response()->json([
            'message' => 'Appointment successfully set and user notified.',
        ]);
    }
}
