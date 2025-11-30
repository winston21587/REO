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
use App\Models\Meeting;
use App\Models\AgendaItem;
use Carbon\Carbon;


use Illuminate\Support\Facades\Hash;

class admin extends Controller
{

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'college' => 'required|string',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'college' => $request->college,
            'role' => 'researcher',
            'password' => Hash::make('password'), // Default password
            'email_verified_at' => now(), // Auto-verify since admin created it
            'external_user' => false,
        ]);

        return back()->with('success', 'Researcher added successfully!');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $message = 'User deactivated successfully.';
        } else {
            $user->email_verified_at = now();
            $message = 'User activated successfully.';
        }
        
        $user->save();
        
        return back()->with('success', $message);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return back()->with('success', 'User deleted successfully.');
    }

    public function manageStaff()
    {
        $staff = User::where('role', 'reo_member')->get();
        
        $stats = [
            'total' => $staff->count(),
            'officers' => $staff->whereIn('position', ['Chair', 'Vice-Chair', 'Secretary'])->count(),
            'trained' => $staff->where('training_completed', true)->count(),
            'quorum' => ($staff->count() >= 5 && 
                         $staff->where('member_type', 'Non-Scientist')->count() >= 1 && 
                         $staff->where('member_type', 'Non-Affiliated')->count() >= 1) ? 'Valid' : 'Invalid'
        ];

        return view('admin.manage_staff', compact('staff', 'stats'));
    }

    public function storeStaff(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'position' => 'required|string',
            'member_type' => 'required|in:Scientist,Non-Scientist,Non-Affiliated',
            'expertise' => 'nullable|string', // Comma separated tags
            'college' => 'nullable|string',
            'training_completed' => 'nullable',
        ]);

        // Process expertise tags
        $expertise = $request->expertise ? array_map('trim', explode(',', $request->expertise)) : [];

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => 'reo_member',
            'position' => $request->position,
            'member_type' => $request->member_type,
            'expertise' => $expertise,
            'college' => $request->college,
            'training_completed' => $request->has('training_completed'),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'external_user' => $request->member_type === 'Non-Affiliated',
        ]);

        return back()->with('success', 'Member added successfully!');
    }

    public function updateStaff(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'position' => 'required|string',
            'member_type' => 'required|in:Scientist,Non-Scientist,Non-Affiliated',
            'expertise' => 'nullable|string',
            'college' => 'nullable|string',
            'training_completed' => 'nullable', // Checkbox sends 'on' or nothing
        ]);

        $expertise = $request->expertise ? array_map('trim', explode(',', $request->expertise)) : [];

        $user->update([
            'position' => $request->position,
            'member_type' => $request->member_type,
            'expertise' => $expertise,
            'college' => $request->college,
            'training_completed' => $request->has('training_completed'),
            'external_user' => $request->member_type === 'Non-Affiliated',
        ]);

        return back()->with('success', 'Member updated successfully!');
    }

    public function deleteStaff($id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => 'user']); // Or delete entirely if preferred
        return back()->with('success', 'Member removed successfully!');
    }

    // --- Meetings & Agenda Methods ---



    public function manageUsers(Request $request)
    {
        $query = User::where('role', 'researcher');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by College
        if ($request->has('college') && $request->college != '') {
            $query->where('college', $request->college);
        }

        // Filter by Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status == 'pending') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->paginate(10);
        
        // Full list of WMSU Colleges
        $colleges = [
            "College of Computing Studies",
            "College of Engineering",
            "College of Science and Mathematics",
            "College of Liberal Arts",
            "College of Teacher Education",
            "College of Nursing",
            "College of Criminal Justice Education"
        ];

        return view('admin.manage_users', compact('users', 'colleges'));
    }

    public function analytics()
    {
        // 1. Total Submissions
        $totalSubmissions = Research_title::count();

        // 2. Approved (For Initial Review)
        // Adjust status string if needed based on your DB
        $approvedCount = Research_title::where('Status', 'For Initial Review')->count();
        
        // Calculate Approval Rate
        $approvalRate = $totalSubmissions > 0 ? round(($approvedCount / $totalSubmissions) * 100) : 0;

        // 3. Active Researchers
        $activeResearchers = User::where('role', 'researcher')->count();

        // 4. Submission Trends (Monthly for current year)
        $monthlyStats = Research_title::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyStats[$i] ?? 0;
        }

        // 5. Completion Status Breakdown
        $statusCounts = Research_title::selectRaw('Status, COUNT(*) as count')
            ->groupBy('Status')
            ->pluck('count', 'Status')
            ->toArray();

        $doneCount = $statusCounts['Completed'] ?? 0; // Adjust 'Completed' to your actual status
        $activeCount = ($statusCounts['For Initial Review'] ?? 0) + ($statusCounts['Under Review'] ?? 0);
        $pendingCount = $statusCounts['Pending'] ?? 0;

        // Calculate Completion Rate (Example: Done / Total)
        $completionRate = $totalSubmissions > 0 ? round(($doneCount / $totalSubmissions) * 100) : 0;

        // 6. AI Compliance Metrics
        $avgAiScore = round(Research_title::avg('ai_score') ?? 0);
        $humanVerifiedCount = Research_title::where('is_human_verified', true)->count();
        $humanVerifiedRate = $totalSubmissions > 0 ? round(($humanVerifiedCount / $totalSubmissions) * 100) : 0;

        return view('admin.analytics', compact(
            'totalSubmissions', 
            'approvedCount', 
            'approvalRate', 
            'activeResearchers',
            'monthlyData',
            'doneCount',
            'activeCount',
            'pendingCount',
            'completionRate',
            'avgAiScore',
            'humanVerifiedRate'
        ));
    }

    public function index($request)
    {
        return view('admin.analytics');
    }
public function applications(Request $request)
    {
        $query = Research_title::with(['author', 'files', 'adminFiles']);

        // 1. STRICT CONSTRAINT: Only "For Initial Review" and "Revision" statuses
        // This ensures the page ONLY accepts these titles, regardless of other inputs.
        $query->where(function($q) {
            $q->where('Status', 'For Initial Review')
              ->orWhere('Status', 'Complete - Awaiting Hardcopy')
              ->orWhere('Status', 'Hardcopy Received - For Initial Review')
              ->orWhere('Status', 'Under Review');
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
    public function updateStatus(Request $request, $id)
    {
        $submission = Research_title::findOrFail($id);
        $user = User::find($submission->user_id);
        
        $message = ""; 
        $newStatus = "";

        // ---------------------------------------------------------
        // CASE A: Request coming from Triage Modal (has 'classification')
        // ---------------------------------------------------------
        if ($request->has('classification')) {
            // ... (Existing Triage Logic - Keep as is or modify if needed) ...
            // For now, I'll assume this part remains for the "Initial Intake" page.
            // If you want to unify, we can, but the user asked for "Applications" page update.
            
            if ($request->classification === 'Complete') {
                $request->validate(['appointment_date' => 'required|date']);
                $newStatus = 'Complete - Awaiting Hardcopy';
                $submission->Status = $newStatus;
                $submission->save();
                $appointment = Appointment::create([
                    'research_title_id' => $submission->id,
                    'user_id' => $submission->user_id,
                    'appointment_date' => $request->appointment_date,
                    'stage' => 'Hardcopy Submission',
                ]);
                $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
                $message = "Your submission document check is Complete. Please submit the hardcopies by: {$dateFormatted}.";
            } elseif ($request->classification === 'Incomplete') {
                $request->validate(['remarks' => 'nullable|string']);
                $newStatus = 'Incomplete';
                $submission->Status = $newStatus;
                $submission->save();
                $missingDocs = $request->input('missing_requirements', []);
                $message = "Your submission has been marked as Incomplete.";
                if($request->remarks) { $message .= "\n\nGeneral Remarks: " . $request->remarks; }
                if (!empty($missingDocs)) {
                    $message .= "\n\nMissing Requirements / Actions Needed:";
                    foreach($missingDocs as $doc) { $message .= "\n- " . $doc; }
                }
            }
        } 
        // ---------------------------------------------------------
        // CASE B: NEW Update Status Logic (Review Type + Appointment)
        // ---------------------------------------------------------
        elseif ($request->has('review_type')) {
            $request->validate([
                'review_type' => 'required|string', // Expedited, Exempt, Full Review
                'appointment_date' => 'required|date',
            ]);

            $newStatus = 'Under Review'; // Or keep it as 'For Initial Review' but with a type? 
            // Usually, after assigning a type, it goes to "Under Review" or stays in "For Initial Review" until reviewers are assigned.
            // Let's assume it updates the Review_Type column and sets status to 'Under Review' or keeps it.
            // The user said "update status", so let's set it to 'Under Review' or similar.
            // Actually, the user prompt implies this IS the status update.
            
            $submission->Review_Type = $request->review_type;
            
            // Use status_action if provided (e.g. from auto-set JS), otherwise default to 'Under Review'
            if ($request->has('status_action') && !empty($request->status_action)) {
                $submission->Status = $request->status_action;
            } else {
                $submission->Status = 'Under Review'; 
            }
            
            $submission->save();

            // Create Appointment
            Appointment::create([
                'research_title_id' => $submission->id,
                'user_id' => $submission->user_id,
                'appointment_date' => $request->appointment_date,
                'stage' => $request->review_type, // e.g., 'Expedited Review'
            ]);

            // Redirect to Recommendation Letter Form
            // We do NOT finalize the status to "Waiting for Revision" yet.
            // The letter generation step will handle that.
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    // 'redirect' => route('admin.recommendation.form', $id) // REMOVED REDIRECT
                ]);
            }
            
            return redirect()->back()->with('success', 'Review Type updated. Please proceed to generate the Recommendation Letter.');
        }
        // ---------------------------------------------------------
        // CASE C: Status Actions (Revision, Panel, Approved)
        // ---------------------------------------------------------
        elseif ($request->has('status_action') && $request->status_action) {
            $action = $request->status_action;
            $newStatus = $action;
            $submission->Status = $newStatus;
            $submission->save();

            if ($action === 'Waiting for Revision') {
                $message = "Your submission requires revision.";
                if ($request->remarks) {
                    $message .= "\n\nRemarks/Requirements: " . $request->remarks;
                }
            } elseif ($action === 'Panel Deliberation') {
                $request->validate(['appointment_date' => 'required|date']);
                Appointment::create([
                    'research_title_id' => $submission->id,
                    'user_id' => $submission->user_id,
                    'appointment_date' => $request->appointment_date,
                    'stage' => 'Panel Deliberation',
                ]);
                $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
                $message = "Your research is scheduled for Panel Deliberation on: {$dateFormatted}.";
                if ($request->remarks) {
                    $message .= "\n\nRemarks: " . $request->remarks;
                }
            } elseif ($action === 'Approved') {
                $message = "Congratulations! Your research has been Approved.";
                $message .= "\n\nYour Research Ethics Clearance Certificate has been issued.";
                
                // Generate Certificate
                $this->generateCertificate($submission);
            }
        }
        // ---------------------------------------------------------
        // CASE C: Generic Status Update (Fallback)
        // ---------------------------------------------------------
        else {
            $request->validate(['status' => 'required|string']);
            $newStatus = $request->status;
            $submission->Status = $newStatus;
            $submission->save();
            $message = "Your research status has been updated to: {$newStatus}.";
            if ($request->reason) { $message .= " Remarks: {$request->reason}"; }
        }

        // Notification Logic
        UserNotification::create([
            'user_id' => $submission->user_id,
            'research_id' => $submission->id,
            'title' => 'Status Update: ' . $newStatus,
            'message' => $message,
            'type' => 'status_update',
            'is_read' => false
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Status and Review Type updated successfully']);
        }

        return redirect()->back()->with('success', 'Status updated successfully');
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

    public function serveFile($id)
    {
        $file = researcher_files::findOrFail($id);
        
        // Normalize path: remove 'storage/' prefix if present
        $path = str_replace('storage/', '', $file->filepath);
        
        // 1. Check Storage (Public Disk)
        if (Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        }
        
        // 2. Check Public Directory (Direct Access)
        $publicPath = public_path($file->filepath);
        if (file_exists($publicPath)) {
            return response()->file($publicPath);
        }
        
        // 3. Check Storage Path directly (Absolute)
        $storagePath = storage_path('app/public/' . $path);
        if (file_exists($storagePath)) {
            return response()->file($storagePath);
        }

        return abort(404, 'File not found.');
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
            'filepath' => $path, // Storing the path
            'filetype' => 'Result of Review (Admin Generated)',
        ]);

        // E. Return the view for the browser to print
        return response($htmlContent);
    }
    // 3. Recommendation Letter Feature
    public function showRecommendationLetterForm($id)
    {
        $submission = Research_title::with(['author', 'files', 'adminFiles'])->findOrFail($id);
        
        // Check for both old and new filetypes in both relationships
        $hasLetter = $submission->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->isNotEmpty() 
                  || $submission->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->isNotEmpty();
        
        return view('admin.recommendation_letter.form', compact('submission', 'hasLetter'));
    }

    public function viewSavedRecommendationLetter($id)
    {
        $file = researcher_files::where('research_title_id', $id)
            ->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])
            ->latest()
            ->firstOrFail();

        $path = str_replace('storage/', '', $file->filepath);
        
        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'File not found.');
        }

        return response()->file(storage_path('app/public/' . $path));
    }

    public function generateRecommendationLetter(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:research_title_information,id',
            'title' => 'required|string',
            'review_type' => 'required|string',
            'num_sets' => 'nullable|string',
            'envelope_type' => 'nullable|string',
        ]);

        $submission = Research_title::findOrFail($request->id);
        
        // Initialize FPDI
        $pdf = new \setasign\Fpdi\Fpdi();
        
        // Source file
        $templatePath = resource_path('views/letter/Result-of-Review-Form.pdf');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template file not found.');
        }

        $pageCount = $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);
        
        $pdf->AddPage();
        $pdf->useTemplate($tplIdx, 0, 0, 210); // A4 width

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Helper function for checks
        $checkAndMark = function($pdf, $x, $y, $value, $checks) {
            if (is_array($checks) && in_array($value, $checks)) {
                $pdf->SetXY($x, $y);
                $pdf->Write(0, 'X');
            }
        };

        // --- FILL DATA ---
        
        // Title
        $pdf->SetXY(37, 63); 
        // Truncate title if too long or handle multi-line if needed (simple write for now)
        $pdf->Write(0, substr($request->title, 0, 80)); 

        // Review Type
        $pdf->SetXY(47, 70);
        $pdf->Write(0, $request->review_type);

        // Number of Sets
        $pdf->SetXY(105, 225);
        $pdf->Write(0, $request->num_sets);

        // Envelope Type
        $pdf->SetXY(20, 229);
        $pdf->Write(0, $request->envelope_type);

        // Extra Notes
        if($request->extraNotes) {
            $pdf->SetXY(15, 125);
            $pdf->MultiCell(180, 5, $request->extraNotes);
        }

        // Checkboxes
        $pdf->SetFont('Arial', 'B', 12); // Make X bold and slightly larger
        $x = 12; // Base X for checkboxes (adjust if needed, user code had $x undefined but used it)
        // Looking at user code: checkAndMark($pdf, $x, 96.5, '1', $protocolChecks);
        // I will assume $x is around 12-15 based on standard forms, let's try 13.
        $x = 13;

        // Protocol/Proposal Checks
        $protocolChecks = $request->input('ethics_review_1', []);
        $checkAndMark($pdf, $x, 96.5, '1', $protocolChecks);
        $checkAndMark($pdf, $x, 101, '2', $protocolChecks);
        $checkAndMark($pdf, $x, 105.49, '3', $protocolChecks);
        $checkAndMark($pdf, $x, 109.98, '4', $protocolChecks);
        $checkAndMark($pdf, $x, 114.47, '5', $protocolChecks);
        $checkAndMark($pdf, $x, 118.96, '6', $protocolChecks);

        // Informed Consent Checks
        $consentChecks = $request->input('ethics_review_2', []);
        $checkAndMark($pdf, $x, 154.5, '1', $consentChecks);
        $checkAndMark($pdf, $x, 158.99, '2', $consentChecks);
        $checkAndMark($pdf, $x, 163.48, '3', $consentChecks);
        $checkAndMark($pdf, $x, 167.97, '4', $consentChecks);
        $checkAndMark($pdf, $x, 172.46, '5', $consentChecks);
        $checkAndMark($pdf, $x, 176.95, '6', $consentChecks);
        $checkAndMark($pdf, $x, 181.44, '7', $consentChecks);
        $checkAndMark($pdf, $x, 185.93, '8', $consentChecks);
        $checkAndMark($pdf, $x, 190.42, '9', $consentChecks);
        $checkAndMark($pdf, $x, 194.91, '10', $consentChecks);
        $checkAndMark($pdf, $x, 199.4, '11', $consentChecks);
        $checkAndMark($pdf, $x, 207, '12', $consentChecks);
        $checkAndMark($pdf, $x, 212.87, '13', $consentChecks);

        // Recommended Actions
        $recommendedActions = $request->input('Recommended_Actions', []);
        $checkAndMark($pdf, 25, 274, '1', $recommendedActions);
        $checkAndMark($pdf, 108, 274, '2', $recommendedActions);

        // Output
        if ($request->action === 'view') {
            return response($pdf->Output('S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Result_of_Review.pdf"',
            ]);
        } else {
            // Save and Send
            $filename = "Result_of_Review_{$submission->id}_" . time() . ".pdf";
            $path = "uploads/research_{$submission->id}/" . $filename;
            
            // Ensure directory exists
            if (!Storage::disk('public')->exists("uploads/research_{$submission->id}")) {
                Storage::disk('public')->makeDirectory("uploads/research_{$submission->id}");
            }

            Storage::disk('public')->put($path, $pdf->Output('S'));

            // Save to DB
            researcher_files::create([
                'research_title_id' => $submission->id,
                'filename' => $filename,
                'filepath' => $path,
                'filetype' => 'Result of Review (Admin Generated)',
            ]);

            // Redirect back to the form with success message
            // The user will manually click "Proceed to Revision"
            return redirect()->back()->with('success', 'Recommendation Letter generated and saved successfully. You can now proceed to the next stage.');
        }
    }

    public function checkFileStatus($id)
    {
        $submission = Research_title::with(['files', 'adminFiles'])->findOrFail($id);
        $hasLetter = $submission->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->isNotEmpty()
                  || $submission->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->isNotEmpty();
        
        return response()->json([
            'has_recommendation_letter' => $hasLetter
        ]);
    }

    public function finalizeReview($id)
    {
        $submission = Research_title::findOrFail($id);
        
        $userMessage = '';
        if ($submission->Review_Type === 'Full Board Review') {
            $submission->Status = 'Panel Deliberation';
            $message = 'Status updated to Panel Deliberation.';
            $redirectRoute = 'admin.applications'; 
            $userMessage = "Your research protocol has been moved to Panel Deliberation. Please wait for further updates regarding the schedule.";
        } else {
            $submission->Status = 'Waiting for Revision';
            $message = 'Status updated to Waiting for Revision.';
            $redirectRoute = 'admin.revisions';
            $userMessage = "Your research protocol requires revisions. Please check the recommendation letter and submit the necessary changes.";
        }
        
        $submission->save();
        
        // Notify the user
        UserNotification::create([
            'user_id' => $submission->user_id,
            'research_id' => $submission->id,
            'title' => 'Status Update: ' . $submission->Status,
            'message' => $userMessage,
            'type' => 'status_update',
            'is_read' => false
        ]);
        
        return redirect()->route($redirectRoute)->with('success', $message);
    }


    private function generateCertificate($submission)
    {
        $user = User::find($submission->user_id);
        
        $pdf = new Fpdi();
        $pdf->AddPage();
        
        // Header
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'RESEARCH ETHICS CLEARANCE', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Date
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Date: ' . date('F j, Y'), 0, 1, 'R');
        $pdf->Ln(10);
        
        // Body
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, "This is to certify that the research protocol titled:");
        $pdf->Ln(5);
        
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->MultiCell(0, 10, strtoupper($submission->Study_Protocol_title), 0, 'C');
        $pdf->Ln(5);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, "Submitted by:");
        $pdf->Ln(5);
        
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->MultiCell(0, 10, strtoupper($user->first_name . ' ' . $user->last_name), 0, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', '', 12);
        $text = "Has been reviewed by the Research Ethics Office and has been granted ETHICAL CLEARANCE. The researcher is hereby authorized to proceed with the data collection as described in the approved protocol.";
        $pdf->MultiCell(0, 10, $text);
        $pdf->Ln(20);
        
        // Signature
        $pdf->Cell(0, 10, '_________________________', 0, 1, 'R');
        $pdf->Cell(0, 10, 'Ethics Review Chair       ', 0, 1, 'R');

        // Output
        $fileName = 'Clearance_' . $submission->id . '.pdf';
        $filePath = 'certificates/' . $fileName;
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists('certificates')) {
            Storage::disk('public')->makeDirectory('certificates');
        }
        
        $pdf->Output('F', storage_path('app/public/' . $filePath));
        
        // Save to Database
        researcher_files::create([
            'research_title_id' => $submission->id,
            'filename' => 'Ethics Clearance Certificate',
            'filetype' => 'certificate',
            'filepath' => 'storage/' . $filePath,
            'user_id' => $submission->user_id,
        ]);
    }

    public function revisions(Request $request)
    {
        $query = Research_title::with('author')
            ->whereIn('Status', ['Waiting for Revision', 'Revision Submitted', 'Checking of Revisions', 'Panel Deliberation']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Study_Protocol_title', 'like', "%{$search}%")
                  ->orWhereHas('author', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $datas = $query->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.revisions', compact('datas'));
    }

    public function certifications(Request $request)
    {
        $query = Research_title::with(['author', 'files'])
            ->where('Status', 'Approved');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Study_Protocol_title', 'like', "%{$search}%")
                  ->orWhereHas('author', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $datas = $query->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.certifications', compact('datas'));
    }
    public function meetings()
    {
        $upcomingMeetings = Meeting::where('meeting_date', '>=', now())
            ->orderBy('meeting_date', 'asc')
            ->get();
        
        $nextMeeting = $upcomingMeetings->first();

        return view('admin.meetings.index', compact('upcomingMeetings', 'nextMeeting'));
    }

    public function storeMeeting(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'meeting_date' => 'required|date',
            'venue' => 'nullable|string',
        ]);

        $meeting = Meeting::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'meeting_date' => $validated['meeting_date'],
            'venue' => $validated['venue'],
            'status' => 'Scheduled',
            'agenda_status' => 'Draft',
        ]);

        // Pre-populate standard agenda items
        $standardItems = [
            ['section' => 'Preliminary', 'content' => 'Call to Order', 'order' => 1],
            ['section' => 'Preliminary', 'content' => 'Invocation', 'order' => 2],
            ['section' => 'Preliminary', 'content' => 'Determination of Quorum', 'order' => 3],
            ['section' => 'Preliminary', 'content' => 'Approval of Agenda', 'order' => 4],
            ['section' => 'Preliminary', 'content' => 'Reading and Approval of Minutes', 'order' => 5],
            ['section' => 'Business Arising', 'content' => 'Review of Action Items', 'order' => 6],
            ['section' => 'New Business', 'content' => 'Protocol Review', 'order' => 7],
            ['section' => 'Other Matters', 'content' => 'Announcements', 'order' => 8],
            ['section' => 'Closing', 'content' => 'Adjournment', 'order' => 9],
        ];

        foreach ($standardItems as $item) {
            $meeting->agendaItems()->create($item);
        }

        return back()->with('success', 'Meeting scheduled successfully.');
    }

    public function showMeeting($id)
    {
        $meeting = Meeting::with(['agendaItems' => function($query) {
            $query->orderBy('order', 'asc');
        }])->findOrFail($id);

        return view('admin.meetings.show', compact('meeting'));
    }

    public function destroyMeeting($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->delete();
        return back()->with('success', 'Meeting deleted successfully.');
    }

    public function storeAgendaItem(Request $request, $meetingId)
    {
        $request->validate([
            'section' => 'required|string',
            'content' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        AgendaItem::create([
            'meeting_id' => $meetingId,
            'section' => $request->section,
            'content' => $request->content,
            'order' => $request->order,
        ]);

        return back()->with('success', 'Agenda item added successfully.');
    }

    public function updateAgendaItem(Request $request, $id)
    {
        $item = AgendaItem::findOrFail($id);
        
        $request->validate([
            'section' => 'required|string',
            'content' => 'nullable|string',
        ]);

        $item->update([
            'section' => $request->section,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Agenda item updated successfully.');
    }

    public function destroyAgendaItem($id)
    {
        $item = AgendaItem::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Agenda item removed successfully.');
    }

    public function updateMeetingStatus(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        
        if ($request->has('agenda_status')) {
            $meeting->agenda_status = $request->agenda_status;
        }

        $meeting->save();
        return back()->with('success', 'Meeting status updated successfully.');
    }


}
