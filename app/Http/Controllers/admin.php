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
use Carbon\Carbon;


class admin extends Controller
{

    public function index($request)
    {
        return view('admin.analytics');
    }
public function applications(Request $request)
    {
        $query = Research_title::with('author');

        // 1. STRICT CONSTRAINT: Only "For Initial Review" and "Revision" statuses
        // This ensures the page ONLY accepts these titles, regardless of other inputs.
        $query->where(function($q) {
            $q->where('Status', 'For Initial Review')
              ->orWhere('Status', 'LIKE', '%Revision%'); // Matches 'Waiting for Revision', 'Checking of Revisions', etc.
        });

        // 2. Handle Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Study_Protocol_title', 'like', "%{$search}%")
                  ->orWhereHas('author', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%"); 
                  });
            });
        }

        // 3. Handle Specific Filter (e.g., if user selects just "Waiting for Revision")
        if ($request->has('status') && !empty($request->status)) {
            $query->where('Status', $request->status);
        }

        $datas = $query->orderBy('created_at', 'desc')->get();

        // Fetch Reviewers for the modal
        $reviewers = User::whereIn('role', ['admin', 'researcher', 'reviewer'])->get(); 

        return view('admin.applications', compact('datas', 'reviewers'));
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
        $submission = Research_title::findOrFail($id);
        $user = User::find($submission->user_id);
        
        $message = ""; // Message to be sent to user
        $newStatus = "";

        // ---------------------------------------------------------
        // CASE A: Request coming from Triage Modal (has 'classification')
        // ---------------------------------------------------------
        if ($request->has('classification')) {
            
            if ($request->classification === 'Complete') {
                // Validate Appointment
                $request->validate(['appointment_date' => 'required|date']);

                $newStatus = 'For Initial Review';
                $submission->Status = $newStatus;
                $submission->save();

                // Create Appointment
                $appointment = Appointment::create([
                    'research_title_id' => $submission->id,
                    'user_id' => $submission->user_id,
                    'appointment_date' => $request->appointment_date,
                    'stage' => 'Initial Review',
                ]);

                // Format Message
                $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
                $message = "Your submission document check is Complete. We have set your Initial Review Appointment on: {$dateFormatted}.";

                $message = "Your submission has been marked as COMPLETE.\n\n";
                $message .= "1. Appointment Set: Your Initial Review is scheduled for {$dateFormatted}.\n";
                $message .= "2. Action Required: Please submit the HARD COPY of your protocol to the Research Ethics Office (REO).";
                // (Optional) Send standard Appointment Email if you use Mailables
                // Mail::to($user->email)->send(new AppointmentMail($appointment));

            } elseif ($request->classification === 'Incomplete') {
                // Validate Remarks
                $request->validate(['remarks' => 'nullable|string']);

                $newStatus = 'Incomplete';
                $submission->Status = $newStatus;
                $submission->save();

                // Handle the List of Missing Requirements
                // The Javascript sends this as an array: missing_requirements[]
                $missingDocs = $request->input('missing_requirements', []);
                
                $message = "Your submission has been marked as Incomplete.";
                
                // Append General Remarks
                if($request->remarks) {
                    $message .= "\n\nGeneral Remarks: " . $request->remarks;
                }

                // Append List of Missing Files
                if (!empty($missingDocs)) {
                    $message .= "\n\nMissing Requirements / Actions Needed:";
                    foreach($missingDocs as $doc) {
                        $message .= "\n- " . $doc;
                    }
                }
            }
        } 
        // ---------------------------------------------------------
        // CASE B: Generic Status Update (Simple dropdowns from other pages)
        // ---------------------------------------------------------
        else {
            $request->validate(['status' => 'required|string']);
            
            $newStatus = $request->status;
            $submission->Status = $newStatus;
            $submission->save();

            $message = "Your research status has been updated to: {$newStatus}.";
            if ($request->reason) {
                $message .= " Remarks: {$request->reason}";
            }
        }

        // ---------------------------------------------------------
        //  NOTIFICATIONS (Database + Email via Notification Class)
        // ---------------------------------------------------------
        
        // 1. Create entry in custom 'user_notifications' table (If you are using the custom table approach)
        UserNotification::create([
            'user_id' => $submission->user_id,
            'research_id' => $submission->id,
            'title' => 'Submission Status Update',
            'message' => $message,
            'type' => ($newStatus === 'Incomplete') ? 'warning' : 'info',
            'is_read' => false
        ]);

        // 2. (Optional) If you want to use the Laravel Notification Class as well:
        // if ($user) {
        //    Notification::send($user, new TitleStatusUpdated($submission, $newStatus, null, $message));
        // }

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

public function assignReviewers(Request $request, $id)
    {
        $request->validate([
            'primary_reviewer' => 'required|exists:users,id',
            'secondary_reviewer' => 'required|exists:users,id|different:primary_reviewer',
        ]);

        $submission = Research_title::findOrFail($id);
        
        // Assuming you have these columns in your 'research_titles' table.
        // If not, you need to create a migration to add them.
        $submission->primary_reviewer_id = $request->primary_reviewer;
        $submission->secondary_reviewer_id = $request->secondary_reviewer;
        $submission->Status = 'Under Review'; // Optional: Auto-update status
        $submission->save();

        // Optional: Send Notification to Reviewers
        // Notification::send(User::find($request->primary_reviewer), new ReviewerAssigned($submission));

        return response()->json(['success' => true, 'message' => 'Reviewers assigned successfully.']);
    }
    public function setInitialReview(Request $request, $id)
    {
        $request->validate([
            'classification' => 'required|string',
            'appointment_date' => 'nullable|date',
        ]);

        // Find the research title
        $research = Research_title::findOrFail($id);

        if ($request->classification === 'Complete') {
            $request->validate([
                'appointment_date' => 'required|date',
            ]);

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
                'message' => 'Submission marked as Complete. Appointment set and user notified.',
            ]);
        } elseif ($request->classification === 'Incomplete') {
            $research->Status = 'Incomplete';
            $research->save();

            return response()->json([
                'message' => 'Submission marked as Incomplete.',
            ]);
        }

        return response()->json(['message' => 'Invalid classification.'], 400);
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

    // 1. Show the Checklist Form (Replaces RC_letter.php)
    public function showLetterForm($id)
    {
        $submission = Research_title::with('author')->findOrFail($id);
        return view('admin.letter_generator.form', compact('submission'));
    }

    // 2. Show the Printable Letter (The Output)
public function previewLetter(Request $request)
    {
        $data = $request->validate([
            'submission_id' => 'required',
            'protocol_issues' => 'array',
            'consent_issues' => 'array',
            'recommended_actions' => 'array',
            'review_type' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $submission = Research_title::with('author')->findOrFail($request->submission_id);

        // A. Render View to String
        $htmlContent = view('admin.letter_generator.print', compact('submission', 'data'))->render();

        // B. Generate Filename
        $timestamp = now()->format('Ymd_His');
        $filename = "Result_of_Review_{$submission->id}_{$timestamp}.html";
        $path = "uploads/research_{$submission->id}/" . $filename;

        // C. Save HTML File to Storage (public disk)
        Storage::disk('public')->put($path, $htmlContent);

        // D. Save Record in Database
        researcher_files::create([
            'research_title_id' => $submission->id, // Ensure this matches your FK column
            'filename' => $filename,
            'file_path' => $path, // Storing the path
            'file_type' => 'Result of Review (Admin Generated)',
        ]);

        // E. Return the view for the browser to print
        return response($htmlContent);
    }
}
