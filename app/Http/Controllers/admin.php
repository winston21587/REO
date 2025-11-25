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
use App\Models\UserNotification;
class admin extends Controller
{

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

    public function meetings()
    {
        // Mock data for meetings
        $meetings = [
            ['id' => 1, 'title' => 'Initial Review Board', 'date' => '2025-10-15', 'time' => '09:00 AM', 'location' => 'Conference Room A', 'status' => 'Scheduled', 'agenda_count' => 5],
            ['id' => 2, 'title' => 'Expedited Review Panel', 'date' => '2025-10-18', 'time' => '02:00 PM', 'location' => 'Online (Zoom)', 'status' => 'Scheduled', 'agenda_count' => 3],
            ['id' => 3, 'title' => 'Policy Revision Meeting', 'date' => '2025-10-25', 'time' => '10:00 AM', 'location' => 'Conference Room B', 'status' => 'Draft', 'agenda_count' => 0],
        ];
        return view('admin.meetings', compact('meetings'));
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
        // Mock Data for Pending Submissions
        // $pendingSubmissions = collect([
        //     (object)[
        //         'id' => 101,
        //         'Study_Protocol_title' => 'Impact of Remote Learning on Student Mental Health',
        //         'Research_Category' => 'Social Science',
        //         'created_at' => \Carbon\Carbon::now()->subDays(2),
        //         'author' => (object)['first_name' => 'Maria', 'last_name' => 'Clara', 'college' => 'College of Education']
        //     ],
        //     (object)[
        //         'id' => 102,
        //         'Study_Protocol_title' => 'Biodiversity Assessment of Mount Makiling',
        //         'Research_Category' => 'Environmental Science',
        //         'created_at' => \Carbon\Carbon::now()->subDays(5),
        //         'author' => (object)['first_name' => 'Jose', 'last_name' => 'Rizal', 'college' => 'College of Forestry']
        //     ],
        //     (object)[
        //         'id' => 103,
        //         'Study_Protocol_title' => 'Telemedicine Adoption in Rural Health Units',
        //         'Research_Category' => 'Public Health',
        //         'created_at' => \Carbon\Carbon::now()->subDays(1),
        //         'author' => (object)['first_name' => 'Apolinario', 'last_name' => 'Mabini', 'college' => 'College of Medicine']
        //     ]
        // ]);

        // // Mock Data for Incomplete Submissions
        // $incompleteSubmissions = collect([
        //     (object)[
        //         'id' => 201,
        //         'Study_Protocol_title' => 'AI-Driven Traffic Management System',
        //         'Research_Category' => 'Technology',
        //         'created_at' => \Carbon\Carbon::now()->subWeeks(1),
        //         'author' => (object)['first_name' => 'Andres', 'last_name' => 'Bonifacio', 'college' => 'College of Engineering']
        //     ],
        //     (object)[
        //         'id' => 202,
        //         'Study_Protocol_title' => 'Traditional Healing Practices in Rural Areas',
        //         'Research_Category' => 'Anthropology',
        //         'created_at' => \Carbon\Carbon::now()->subWeeks(2),
        //         'author' => (object)['first_name' => 'Gabriela', 'last_name' => 'Silang', 'college' => 'College of Arts and Sciences']
        //     ],
        //     (object)[
        //         'id' => 203,
        //         'Study_Protocol_title' => 'Microplastic Contamination in Laguna de Bay',
        //         'Research_Category' => 'Environmental Science',
        //         'created_at' => \Carbon\Carbon::now()->subWeeks(3),
        //         'author' => (object)['first_name' => 'Emilio', 'last_name' => 'Aguinaldo', 'college' => 'College of Agriculture']
        //     ]
        // ]);

        // 2. Fetch Pending Submissions (Recent Submissions)
    // Adjust 'Pending' to the exact string you use in your DB (e.g., 'For Initial Review' or 'Submitted')
        $pendingSubmissions = Research_title::where('Status', 'Pending') 
                                ->orderBy('created_at', 'desc') // Show newest first
                                ->get();

        // 3. Fetch Incomplete Submissions
        $incompleteSubmissions = Research_title::where('Status', 'Incomplete')
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Fallback to DB if needed, or just use mock for demo
        // $pendingSubmissions = Research_title::with('author')->where('Status', 'Pending')->get();
        // $incompleteSubmissions = Research_title::with('author')->where('Status', 'Incomplete')->get();

        return view('admin.NewSubmissions', compact('pendingSubmissions', 'incompleteSubmissions'));
    }

// public function updateStatus(Request $request, $id)
// {
//     $request->validate([
//         'status' => 'required|string',
//         'appointment_date' => 'nullable|date'
//     ]);

//     $submission = Research_title::findOrFail($id);
//     $submission->Status = $request->status;
//     $submission->save();

//     // If the admin marked as "For Initial Review"
//     if ($request->status === 'For Initial Review') {
//         $appointment = Appointment::create([
//             'research_title_id' => $submission->id,
//             'user_id' => $submission->user_id,
//             'appointment_date' => $request->appointed_date,
//         ]);

//         // Notify the user
//         $user = User::find($submission->user_id);
//         if ($user) {
//             Notification::send($user, new SubmissionAppointed($submission, $appointment));
//         }
//     }

//     return response()->json(['success' => true]);
// }


public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string',
        'appointment_date' => 'nullable|date',
        'reason' => 'nullable|string' // Remarks/Reason
    ]);

    $submission = Research_title::findOrFail($id);
    $submission->Status = $request->status;
    $submission->save();

    // --- START CUSTOM NOTIFICATION LOGIC ---
    $message = "Your research '{$submission->Study_Protocol_title}' status has been updated to: {$request->status}.";
    
    if ($request->reason) {
        $message .= " Remarks: {$request->reason}";
    }

    if ($request->appointment_date) {
        $date = \Carbon\Carbon::parse($request->appointment_date)->format('F j, Y');
        $message .= " Appointment Date: {$date}.";
    }

    UserNotification::create([
        'user_id' => $submission->user_id,
        'research_id' => $submission->id,
        'title' => 'Status Update',
        'message' => $message,
        'type' => 'info',
        'is_read' => false
    ]);
    // --- END CUSTOM NOTIFICATION LOGIC ---

    // Handle Appointment creation if needed (existing logic)
    if ($request->status === 'For Initial Review') {
        Appointment::create([
            'research_title_id' => $submission->id,
            'user_id' => $submission->user_id,
            'appointment_date' => $request->appointed_date, // Note: check if this is 'appointment_date' or 'appointed_date' in your form
        ]);
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
    public function viewFiles($id)
    {
        // Mock Data Handling for Demo
        $mockData = collect([
            101 => (object)[
                'id' => 101,
                'Study_Protocol_title' => 'Impact of Remote Learning on Student Mental Health',
                'Research_Category' => 'Social Science',
                'created_at' => \Carbon\Carbon::now()->subDays(2),
                'reoc_code' => 'REO-2024-001',
                'author' => (object)['first_name' => 'Maria', 'last_name' => 'Clara', 'college' => 'College of Education', 'email' => 'maria@example.com'],
                'files' => collect([
                    (object)['filename' => 'Protocol_Draft_v1.pdf'],
                    (object)['filename' => 'Informed_Consent.pdf']
                ])
            ],
            102 => (object)[
                'id' => 102,
                'Study_Protocol_title' => 'Biodiversity Assessment of Mount Makiling',
                'Research_Category' => 'Environmental Science',
                'created_at' => \Carbon\Carbon::now()->subDays(5),
                'reoc_code' => 'REO-2024-002',
                'author' => (object)['first_name' => 'Jose', 'last_name' => 'Rizal', 'college' => 'College of Forestry', 'email' => 'jose@example.com'],
                'files' => collect([
                    (object)['filename' => 'Field_Study_Plan.pdf'],
                    (object)['filename' => 'Permits.pdf']
                ])
            ],
            103 => (object)[
                'id' => 103,
                'Study_Protocol_title' => 'Telemedicine Adoption in Rural Health Units',
                'Research_Category' => 'Public Health',
                'created_at' => \Carbon\Carbon::now()->subDays(1),
                'reoc_code' => 'REO-2024-003',
                'author' => (object)['first_name' => 'Apolinario', 'last_name' => 'Mabini', 'college' => 'College of Medicine', 'email' => 'apol@example.com'],
                'files' => collect([
                    (object)['filename' => 'Research_Proposal.pdf']
                ])
            ],
            201 => (object)[
                'id' => 201,
                'Study_Protocol_title' => 'AI-Driven Traffic Management System',
                'Research_Category' => 'Technology',
                'created_at' => \Carbon\Carbon::now()->subWeeks(1),
                'reoc_code' => 'REO-2024-004',
                'author' => (object)['first_name' => 'Andres', 'last_name' => 'Bonifacio', 'college' => 'College of Engineering', 'email' => 'andres@example.com'],
                'files' => collect([
                    (object)['filename' => 'System_Architecture.pdf']
                ])
            ],
            202 => (object)[
                'id' => 202,
                'Study_Protocol_title' => 'Traditional Healing Practices in Rural Areas',
                'Research_Category' => 'Anthropology',
                'created_at' => \Carbon\Carbon::now()->subWeeks(2),
                'reoc_code' => 'REO-2024-005',
                'author' => (object)['first_name' => 'Gabriela', 'last_name' => 'Silang', 'college' => 'College of Arts and Sciences', 'email' => 'gabriela@example.com'],
                'files' => collect([
                    (object)['filename' => 'Interview_Guide.pdf']
                ])
            ],
            203 => (object)[
                'id' => 203,
                'Study_Protocol_title' => 'Microplastic Contamination in Laguna de Bay',
                'Research_Category' => 'Environmental Science',
                'created_at' => \Carbon\Carbon::now()->subWeeks(3),
                'reoc_code' => 'REO-2024-006',
                'author' => (object)['first_name' => 'Emilio', 'last_name' => 'Aguinaldo', 'college' => 'College of Agriculture', 'email' => 'emilio@example.com'],
                'files' => collect([
                    (object)['filename' => 'Lab_Results.pdf']
                ])
            ]
        ]);

        if ($mockData->has($id)) {
            $researchTitle = $mockData->get($id);
            return view('admin.view_files', compact('researchTitle'));
        }

        $researchTitle = Research_title::with('author', 'files')->findOrFail($id);
        return view('admin.view_files', compact('researchTitle'));
    }
}
