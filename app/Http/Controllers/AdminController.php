<?php

namespace App\Http\Controllers;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\DocumentRequirement;
use App\Models\SubmissionFeedback;
use App\Models\User;
use App\Models\Researcher;
use App\Models\Admin;
use App\Models\College;
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

class AdminController extends Controller
{

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'affiliation' => 'required|in:internal,external',
            'college' => 'required_if:affiliation,internal',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => 'researcher',
            'password' => Hash::make($request->password), // User-defined password
            'email_verified_at' => now(), // Auto-verify since admin created it
        ]);

        Researcher::create([
            'user_id' => $user->id,
            'college' => $request->affiliation === 'external' ? null : $request->college,
            'external_user' => $request->affiliation === 'external',
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


    // --- Meetings & Agenda Methods ---



    public function manageUsers(Request $request)
    {
        $query = User::where('role', 'researcher');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by College
        if ($request->has('college') && $request->college != '') {
            $query->whereHas('researcher', function($q) use ($request) {
                $q->where('college', $request->college);
            });
        }

        // Filter by Status (Affiliation)
        if ($request->has('status') && $request->status != '') {
            $query->whereHas('researcher', function($q) use ($request) {
                if ($request->status == 'internal') {
                    $q->where('external_user', false);
                } elseif ($request->status == 'external') {
                    $q->where('external_user', true);
                }
            });
        }

        $users = $query->paginate(10);


        // Full list of WMSU Colleges
        $colleges = College::all();

        // $colleges = [
        //     "College of Computing Studies",
        //     "College of Engineering",
        //     "College of Science and Mathematics",
        //     "College of Liberal Arts",
        //     "College of Teacher Education",
        //     "College of Nursing",
        //     "College of Criminal Justice Education"
        // ];

        return view('admin.manage_users', compact('users', 'colleges'));
    }

    public function analytics(Request $request)
    {
        // Date Filter Logic
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('m'));

        $availableYears = Research_title::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // 1. Total Submissions (All time)
        $totalSubmissions = Research_title::count();

        // 2. Approved (For Initial Review)
        $approvedCount = Research_title::where('Status', 'For Initial Review')->count();

        // Calculate Approval Rate
        $approvalRate = $totalSubmissions > 0 ? round(($approvedCount / $totalSubmissions) * 100) : 0;

        // 3. Active Researchers
        $activeResearchers = User::where('role', 'researcher')->count();

        // 4. Submission Trends (Daily for selected month/year)
        // Use the selected month and year to determine days in that specific month
        $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->daysInMonth;

        $dailyStats = Research_title::selectRaw('DAY(created_at) as day, COUNT(*) as count')
            ->whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth)
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();

        // Fill missing days with 0
        $dailyData = [];
        $dayLabels = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dailyData[] = $dailyStats[$i] ?? 0;
            $dayLabels[] = (string) $i;
        }

        // 5. Pie Chart: Review Type Distribution (Filtered by Selected Year)
        $reviewTypeStats = Research_title::selectRaw('Review_Type, COUNT(*) as count')
            ->whereYear('created_at', $selectedYear)
            ->groupBy('Review_Type')
            ->whereNotNull('Review_Type')
            ->pluck('count', 'Review_Type')
            ->toArray();

        // 6. Pie Chart: Approval Status (Filtered by Selected Year)
        $statusStats = Research_title::selectRaw('Status, COUNT(*) as count')
            ->whereYear('created_at', $selectedYear)
            ->whereIn('Status', ['Approved', 'Disapproved'])
            ->groupBy('Status')
            ->pluck('count', 'Status')
            ->toArray();

        // 7. Completion Status Breakdown (All time / Current snapshots)
        $statusCounts = Research_title::selectRaw('Status, COUNT(*) as count')
            ->groupBy('Status')
            ->pluck('count', 'Status')
            ->toArray();

        $doneCount = $statusCounts['Completed'] ?? 0;
        $activeCount = ($statusCounts['For Initial Review'] ?? 0) + ($statusCounts['Under Review'] ?? 0);
        $pendingCount = $statusCounts['Pending'] ?? 0;

        // Calculate Completion Rate (Example: Done / Total)
        $completionRate = $totalSubmissions > 0 ? round(($doneCount / $totalSubmissions) * 100) : 0;

        // 6. AI Compliance Metrics
        $avgAiScore = round(Research_title::avg('ai_score') ?? 0);
        $humanVerifiedCount = Research_title::where('is_human_verified', true)->count();
        $humanVerifiedRate = $totalSubmissions > 0 ? round(($humanVerifiedCount / $totalSubmissions) * 100) : 0;

        return view('admin.Analytics', compact(
            'totalSubmissions',
            'approvedCount',
            'approvalRate',
            'activeResearchers',
            'dailyData',
            'dayLabels',
            'reviewTypeStats',
            'statusStats',
            'doneCount',
            'activeCount',
            'pendingCount',
            'completionRate',
            'avgAiScore',
            'humanVerifiedRate',
            'selectedYear',
            'selectedMonth',
            'availableYears'
        ));
    }

    public function superAdminAnalytics(Request $request)
    {
        $view = $this->analytics($request);
        return view('super_admin.analytics', $view->getData());
    }

    public function index($request)
    {
        return view('admin.Analytics');
    }
    public function applications(Request $request)
    {
        $query = Research_title::with(['researcher.user', 'files', 'adminFiles']);

        // Base Statuses allowed in Active Protocols
        $allowedStatuses = [
            'For Initial Review',
            'Complete - Awaiting Hardcopy',
            'Hardcopy Received - For Initial Review',
            'Under Review',
            'Waiting for Revision',
            'Revision Submitted', // Ensure this maps correctly if researchers use it
            'Submission of Revisions / Resubmission',
            'Checking of Revisions'
        ];

        // 1. Handle Status Filters (Checkboxes)
        if ($request->filled('statuses') && is_array($request->statuses)) {
            // Map generic groups to actual DB statuses if needed
            $filterStatuses = [];
            foreach ($request->statuses as $status) {
                if ($status === 'For Initial Review') {
                    $filterStatuses = array_merge($filterStatuses, ['For Initial Review', 'Complete - Awaiting Hardcopy', 'Hardcopy Received - For Initial Review']);
                } else {
                    $filterStatuses[] = $status;
                }
            }
            // Ensure they are only filtering within allowed statuses
            $validFilters = array_intersect($filterStatuses, $allowedStatuses);
            $query->whereIn('Status', !empty($validFilters) ? $validFilters : $allowedStatuses);
        } else {
            // Default Constraint: Only allowed active statuses
            $query->whereIn('Status', $allowedStatuses);
        }

        // 2. Handle Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Study_Protocol_title', 'like', "%{$search}%")
                    ->orWhereHas('researcher.user', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // 3. Handle Review Type Filters (Checkboxes)
        if ($request->filled('review_types') && is_array($request->review_types)) {
            $query->whereIn('Review_Type', $request->review_types);
        }

        // 4. Handle Reviewer Assignment (Radio)
        if ($request->filled('assignment')) {
            if ($request->assignment === 'Unassigned') {
                $query->where(function($q) {
                    $q->whereNull('assigned_reviewers')
                      ->orWhereJsonLength('assigned_reviewers', 0)
                      ->orWhere('assigned_reviewers', '[]');
                });
            } elseif ($request->assignment === 'Assigned') {
                $query->whereNotNull('assigned_reviewers')
                      ->where('assigned_reviewers', '!=', '[]'); // Depending on how DB casts it
            }
        }

        // 5. Handle Sorting
        if ($request->sort_by === 'Title') {
            $query->orderBy('Study_Protocol_title', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $datas = $query->paginate(5)->withQueryString();

        // Fetch Reviewers for the modal
        $reviewers = User::where('role', 'reviewer')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.active_protocols_list', compact('datas', 'reviewers'))->render()
            ]);
        }

        return view('admin.applications', compact('datas', 'reviewers'));
    }



    public function GetReview()
    {
        $datas = Research_title::with('researcher.user')
            ->where('Status', 'For Initial Review')
            ->get();
        return view('admin.Review', compact('datas'));
    }


    public function newSubmissions(Request $request)
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


        // 2. Fetch Pending Submissions (Recent Submissions: Pending + Corrections Submitted)
        $pendingQuery = Research_title::with('revisionLogs')->whereIn('Status', ['Pending', 'Corrections Submitted']);

        // Search Filter
        if ($request->filled('recent_search')) {
            $pendingQuery->where('Study_Protocol_title', 'like', '%' . $request->recent_search . '%');
        }

        // Review Type Filter (Array)
        if ($request->filled('recent_review_types') && is_array($request->recent_review_types)) {
            $pendingQuery->whereIn('Review_Type', $request->recent_review_types);
        }

        // Sort Filter
        if ($request->recent_sort == 'Title') {
            $pendingQuery->orderBy('Study_Protocol_title', 'asc');
        } else {
            $pendingQuery->orderBy('created_at', 'desc');
        }

        $pendingSubmissions = $pendingQuery->paginate(3, ['*'], 'pending_page')->withQueryString();


        // 3. Fetch Incomplete Submissions
        $incompleteQuery = Research_title::where('Status', 'Incomplete');

        // Search Filter
        if ($request->filled('incomplete_search')) {
            $incompleteQuery->where('Study_Protocol_title', 'like', '%' . $request->incomplete_search . '%');
        }

        // Review Type Filter (Array)
        if ($request->filled('incomplete_review_types') && is_array($request->incomplete_review_types)) {
            $incompleteQuery->whereIn('Review_Type', $request->incomplete_review_types);
        }

        // Sort Filter
        if ($request->incomplete_sort == 'Title') {
            $incompleteQuery->orderBy('Study_Protocol_title', 'asc');
        } else {
            $incompleteQuery->orderBy('created_at', 'desc');
        }

        $incompleteSubmissions = $incompleteQuery->paginate(3, ['*'], 'incomplete_page')->withQueryString();

        // Fallback to DB if needed, or just use mock for demo
        // $pendingSubmissions = Research_title::with('author')->where('Status', 'Pending')->get();
        // $incompleteSubmissions = Research_title::with('author')->where('Status', 'Incomplete')->get();

        // Check for AJAX Request
        if ($request->ajax()) {
            return response()->json([
                'recent' => view('admin.partials.recent_submissions_list', compact('pendingSubmissions', 'incompleteSubmissions'))->render(),
                'incomplete' => view('admin.partials.incomplete_submissions_list', compact('pendingSubmissions', 'incompleteSubmissions'))->render(),
            ]);
        }

        // Fetch Requirements for the Triage Modal
        $requirements = DocumentRequirement::all();

        return view('admin.NewSubmissions', compact('pendingSubmissions', 'incompleteSubmissions', 'requirements'));
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
        $user = Researcher::find($submission->researcher_id);

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
                $request->validate(['appointment_date' => 'required|date|after:tomorrow']);
                $newStatus = 'Complete - Awaiting Hardcopy';
                $submission->Status = $newStatus;
                $submission->save();
                $appointment = Appointment::create([
                    'research_title_id' => $submission->id,
                    'user_id' => $user->user_id,
                    'appointment_date' => $request->appointment_date,
                    'stage' => 'Hardcopy Submission',
                ]);

                $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
                $message = "Your submission \"{$submission->Study_Protocol_title}\" document check is Complete. Please submit the hardcopies by: {$dateFormatted}.";

            } elseif ($request->classification === 'Incomplete') {
                $request->validate(['remarks' => 'nullable|string']);
                $newStatus = 'Incomplete';
                $submission->Status = $newStatus;
                $submission->save();
                $missingDocs = $request->input('missing_requirements', []);
                
                // Store in Feedbacks Table
                SubmissionFeedback::create([
                    'research_title_id' => $submission->id,
                    'user_id' => auth()->id(),
                    'type' => 'admin_deficiency',
                    'message' => $request->remarks,
                    'missing_requirements' => $missingDocs,
                ]);

                $message = "Your submission \"{$submission->Study_Protocol_title}\" has been marked as Incomplete.";
                if ($request->remarks) {
                    $message .= "\n\nGeneral Remarks: " . $request->remarks;
                }
                if (!empty($missingDocs)) {
                    $message .= "\n\nMissing Requirements / Actions Needed:";
                    foreach ($missingDocs as $doc) {
                        $message .= "\n- " . $doc;
                    }
                }

            } elseif ($request->classification === 'Undo') {
                $newStatus = 'Pending';
                $submission->Status = $newStatus;
                $submission->save();
                $message = "Your submission \"{$submission->Study_Protocol_title}\" status has been reverted to Pending.";

                // Optional: Delete the "Incomplete" notification if you want to be clean
                // UserNotification::where('research_id', $submission->id)
                //     ->where('message', 'like', '%marked as Incomplete%')
                //     ->delete();

            } elseif ($request->classification === 'Undo Complete') {
                $newStatus = 'Pending';
                $submission->Status = $newStatus;
                $submission->save();

                // Delete the appointment
                Appointment::where('research_title_id', $submission->id)
                    ->where('stage', 'Hardcopy Submission')
                    ->delete();

                $message = "Submission reverted to Pending. Appointment cancelled.";
            }
        }
        // ---------------------------------------------------------
        // CASE B: NEW Update Status Logic (Review Type + Appointment)
        // ---------------------------------------------------------
        elseif ($request->has('review_type')) {
            $request->validate([
                'review_type' => 'required|string', // Expedited, Exempt, Full Review
                'appointment_date' => 'required|date|after:tomorrow',
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
                'user_id' => $user->user_id,
                'appointment_date' => $request->appointment_date,
                'stage' => $request->review_type, // e.g., 'Expedited Review'
            ]);

            $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
            // Notify the user about the Review Type assignment
            UserNotification::create([
                'user_id' => $user->user_id,
                'research_id' => $submission->id,
                'title' => 'Status Update: Under Review',
                'message' => "Your research protocol \"{$submission->Study_Protocol_title}\" has been assigned for {$request->review_type}.",
                'type' => 'status_update',
                'is_read' => false
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
        // ---------------------------------------------------------
        elseif ($request->has('status_action') && $request->status_action) {
            $action = $request->status_action;
            $newStatus = $action;

            if ($action === 'Modifications Required') {
                $newStatus = 'Waiting for Revision'; // Map to internal status

                $message = "Your submission \"{$submission->Study_Protocol_title}\" requires modifications.";
                if ($request->remarks) {
                    $message .= "\n\nRemarks/Requirements: " . $request->remarks;
                }
            } elseif ($action === 'Disapproved') {
                $newStatus = 'Disapproved'; // Explicitly set just in case

                $message = "Your research protocol \"{$submission->Study_Protocol_title}\" has been Disapproved.";
                if ($request->remarks) {
                    $message .= "\n\nReason: " . $request->remarks;
                }
            } elseif ($action === 'Panel Deliberation') {
                $request->validate(['appointment_date' => 'required|date|after:tomorrow']);
                Appointment::create([
                    'research_title_id' => $submission->id,
                    'user_id' => $user->user_id,
                    'appointment_date' => $request->appointment_date,
                    'stage' => 'Panel Deliberation',
                ]);
                $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
                $message = "Your research \"{$submission->Study_Protocol_title}\" is scheduled for Panel Deliberation on: {$dateFormatted}.";
                if ($request->remarks) {
                    $message .= "\n\nRemarks: " . $request->remarks;
                }
            } elseif ($action === 'Approved') {
                $message = "Congratulations! Your research \"{$submission->Study_Protocol_title}\" has been Approved.";
                $message .= "\n\nYour Research Ethics Clearance Certificate is ready. Please check with the Research Ethics Office.";
            }

            // Save the final status
            $submission->Status = $newStatus;
            $submission->save();

        }
        // ---------------------------------------------------------
        // CASE C: Generic Status Update (Fallback)
        // ---------------------------------------------------------
        else {
            $request->validate(['status' => 'required|string']);
            $newStatus = $request->status;
            $submission->Status = $newStatus;
            $submission->save();
            $message = "The status of your research \"{$submission->Study_Protocol_title}\" has been updated to: {$newStatus}.";
            if ($request->reason) {
                $message .= " Remarks: {$request->reason}";
            }
        }

        // Notification Logic
        UserNotification::create([
            'user_id' => $user->user_id,
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
            'reviewers' => 'required|array',
            'reviewers.*' => 'exists:users,id', // or users table depending on reviewers setup
        ]);

        $submission = Research_title::findOrFail($id);

        $submission->assigned_reviewers = $request->reviewers;
        $submission->Status = 'Under Review'; // Auto-update status when reviewers assigned
        $submission->save();

        $reviewerNames = User::whereIn('id', $request->reviewers)->get()->map(function($user) {
            return $user->first_name . ' ' . $user->last_name;
        })->implode(', ');

        // Optional: Send Notification to Reviewers
        // foreach($request->reviewers as $reviewer_id) {
        //     Notification::send(User::find($reviewer_id), new ReviewerAssigned($submission));
        // }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Reviewers assigned successfully.']);
        }

        return redirect()->back()->with('success', 'Reviewers assigned successfully.');
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
        // $mockData = collect([
        //     101 => (object)[
        //         'id' => 101,
        //         'Study_Protocol_title' => 'Impact of Remote Learning on Student Mental Health',
        //         'Research_Category' => 'Social Science',
        //         'created_at' => \Carbon\Carbon::now()->subDays(2),
        //         'reoc_code' => 'REO-2024-001',
        //         'author' => (object)['first_name' => 'Maria', 'last_name' => 'Clara', 'college' => 'College of Education', 'email' => 'maria@example.com'],
        //         'files' => collect([
        //             (object)['filename' => 'Protocol_Draft_v1.pdf'],
        //             (object)['filename' => 'Informed_Consent.pdf']
        //         ])
        //     ],
        //     102 => (object)[
        //         'id' => 102,
        //         'Study_Protocol_title' => 'Biodiversity Assessment of Mount Makiling',
        //         'Research_Category' => 'Environmental Science',
        //         'created_at' => \Carbon\Carbon::now()->subDays(5),
        //         'reoc_code' => 'REO-2024-002',
        //         'author' => (object)['first_name' => 'Jose', 'last_name' => 'Rizal', 'college' => 'College of Forestry', 'email' => 'jose@example.com'],
        //         'files' => collect([
        //             (object)['filename' => 'Field_Study_Plan.pdf'],
        //             (object)['filename' => 'Permits.pdf']
        //         ])
        //     ],
        //     103 => (object)[
        //         'id' => 103,
        //         'Study_Protocol_title' => 'Telemedicine Adoption in Rural Health Units',
        //         'Research_Category' => 'Public Health',
        //         'created_at' => \Carbon\Carbon::now()->subDays(1),
        //         'reoc_code' => 'REO-2024-003',
        //         'author' => (object)['first_name' => 'Apolinario', 'last_name' => 'Mabini', 'college' => 'College of Medicine', 'email' => 'apol@example.com'],
        //         'files' => collect([
        //             (object)['filename' => 'Research_Proposal.pdf']
        //         ])
        //     ],
        //     201 => (object)[
        //         'id' => 201,
        //         'Study_Protocol_title' => 'AI-Driven Traffic Management System',
        //         'Research_Category' => 'Technology',
        //         'created_at' => \Carbon\Carbon::now()->subWeeks(1),
        //         'reoc_code' => 'REO-2024-004',
        //         'author' => (object)['first_name' => 'Andres', 'last_name' => 'Bonifacio', 'college' => 'College of Engineering', 'email' => 'andres@example.com'],
        //         'files' => collect([
        //             (object)['filename' => 'System_Architecture.pdf']
        //         ])
        //     ],
        //     202 => (object)[
        //         'id' => 202,
        //         'Study_Protocol_title' => 'Traditional Healing Practices in Rural Areas',
        //         'Research_Category' => 'Anthropology',
        //         'created_at' => \Carbon\Carbon::now()->subWeeks(2),
        //         'reoc_code' => 'REO-2024-005',
        //         'author' => (object)['first_name' => 'Gabriela', 'last_name' => 'Silang', 'college' => 'College of Arts and Sciences', 'email' => 'gabriela@example.com'],
        //         'files' => collect([
        //             (object)['filename' => 'Interview_Guide.pdf']
        //         ])
        //     ],
        //     203 => (object)[
        //         'id' => 203,
        //         'Study_Protocol_title' => 'Microplastic Contamination in Laguna de Bay',
        //         'Research_Category' => 'Environmental Science',
        //         'created_at' => \Carbon\Carbon::now()->subWeeks(3),
        //         'reoc_code' => 'REO-2024-006',
        //         'author' => (object)['first_name' => 'Emilio', 'last_name' => 'Aguinaldo', 'college' => 'College of Agriculture', 'email' => 'emilio@example.com'],
        //         'files' => collect([
        //             (object)['filename' => 'Lab_Results.pdf']
        //         ])
        //     ]
        // ]);

        // if ($mockData->has($id)) {
        //     $researchTitle = $mockData->get($id);
        //     return view('admin.view_files', compact('researchTitle'));
        // }

        $researchTitle = Research_title::with(['researcher.user', 'files', 'adminFiles'])->findOrFail($id);
        $backUrl = url()->previous(route('admin.analytics'));
        return view('admin.view_files', compact('researchTitle', 'backUrl'));
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

    public function manageDocuments()
    {
        return view('admin.manage_documents');
    }

    // 1. Show the Checklist Form (Replaces RC_letter.php)
    public function showLetterForm($id)
    {
        $submission = Research_title::with('researcher.user')->findOrFail($id);
        return view('admin.recommendation_letter.form', compact('submission'));
    }

    // 2. Show the Printable Letter (The Output)
    public function previewLetter(Request $request)
    {
        $data = $request->validate([
            'submission_id' => 'required',
            'ethics_review_1' => 'array',
            'ethics_review_2' => 'array',
            'Recommended_Actions' => 'array',
            'review_type' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $submission = Research_title::with('author')->findOrFail($request->submission_id);

        // A. Render View to String
        $htmlContent = view('admin.recommendation_letter.print', compact('submission', 'data'))->render();

        // B. Generate Filename
        $timestamp = now()->format('Ymd_His');
        $filename = "Result_of_Review_{$submission->id}_{$timestamp}.html";
        $path = "uploads/research_{$submission->id}/" . $filename;

        // C. Save HTML File to Storage (public folder)
        Storage::disk('public_uploads')->put($path, $htmlContent);

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
        $submission = Research_title::with(['researcher.user', 'files', 'adminFiles'])->findOrFail($id);

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

        if (!Storage::disk('public_uploads')->exists($path)) {
            return back()->with('error', 'File not found.');
        }

        return response()->file(public_path($path));
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
        $templatePath = resource_path('views/letter/Result-of-Review-Form(NEW).pdf');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template file not found.');
        }

        $pageCount = $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);

        // Get the size of the imported page to ensure nothing is cut off (e.g. if Legal size)
        $size = $pdf->getTemplateSize($tplIdx);

        // Add page matching the template's orientation and size
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        
        // Import template covering the full page
        $pdf->useTemplate($tplIdx, 0, 0, $size['width'], $size['height']);

        // Prevent automatic page break when writing near the bottom
        $pdf->SetAutoPageBreak(false);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Helper function for checks
        $checkAndMark = function ($pdf, $x, $y, $value, $checks) {
            if (is_array($checks) && in_array($value, $checks)) {
                $pdf->SetXY($x, $y);
                $pdf->Write(0, 'X');
            }
        };

        // --- FILL DATA ---

        // Title
        $pdf->SetXY(28, 58);
        // Truncate title if too long or handle multi-line if needed (simple write for now)
        $pdf->Write(0, substr($request->title, 0, 80));

        // Review Type
        $pdf->SetXY(49, 63);
        $pdf->Write(0, $request->review_type);

        // Number of Sets
        $pdf->SetXY(120, 243);
        $pdf->Write(0, $request->num_sets);

        // Envelope Type
        $pdf->SetXY(20, 248);
        $pdf->Write(0, $request->envelope_type);

        // Extra Notes
        if ($request->extraNotes) {
            $pdf->SetXY(15, 125);
            $pdf->MultiCell(180, 5, $request->extraNotes);
        }

        // Checkboxes
        $pdf->SetFont('Arial', 'B', 12); // Make X bold and slightly larger
        $x = 12.7; // Base X for checkboxes (adjust if needed, user code had $x undefined but used it)
        // Looking at user code: checkAndMark($pdf, $x, 96.5, '1', $protocolChecks);
      

        // Protocol/Proposal Checks
        $protocolChecks = $request->input('ethics_review_1', []);
        $checkAndMark($pdf, $x, 94.5, '1', $protocolChecks);
        $checkAndMark($pdf, $x, 99.5, '2', $protocolChecks);
        $checkAndMark($pdf, $x, 104.5, '3', $protocolChecks);
        $checkAndMark($pdf, $x, 109.5, '4', $protocolChecks);
        $checkAndMark($pdf, $x, 114.5, '5', $protocolChecks);
        $checkAndMark($pdf, $x, 119.5, '6', $protocolChecks);


        // Informed Consent Checks
        $consentChecks = $request->input('ethics_review_2', []);
        $checkAndMark($pdf, $x, 161, '1', $consentChecks);
        $checkAndMark($pdf, $x, 166, '2', $consentChecks);
        $checkAndMark($pdf, $x, 171, '3', $consentChecks);
        $checkAndMark($pdf, $x, 176, '4', $consentChecks);
        $checkAndMark($pdf, $x, 181, '5', $consentChecks);
        $checkAndMark($pdf, $x, 186.2, '6', $consentChecks);
        $checkAndMark($pdf, $x, 191.6, '7', $consentChecks);
        $checkAndMark($pdf, $x, 196.8, '8', $consentChecks);
        $checkAndMark($pdf, $x, 202, '9', $consentChecks);
        $checkAndMark($pdf, $x, 207, '10', $consentChecks);
        $checkAndMark($pdf, $x, 212, '11', $consentChecks);
        $checkAndMark($pdf, $x, 222, '12', $consentChecks);
        $checkAndMark($pdf, $x, 227, '13', $consentChecks);

        // Recommended Actions
        $recommendedActions = $request->input('Recommended_Actions', []);
        $checkAndMark($pdf, 14, 315, '1', $recommendedActions);
        $checkAndMark($pdf, 14, 319.6, '2', $recommendedActions);

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
            if (!Storage::disk('public_uploads')->exists("uploads/research_{$submission->id}")) {
                Storage::disk('public_uploads')->makeDirectory("uploads/research_{$submission->id}");
            }

            Storage::disk('public_uploads')->put($path, $pdf->Output('S'));

            // Save to DB
            researcher_files::create([
                'research_title_id' => $submission->id,
                'filename' => $filename,
                'filepath' => $path,
                'filetype' => 'Result of Review (Admin Generated)',
            ]);

            // Notify the user
            $user = $submission->researcher->user; // Get user from submission
            UserNotification::create([
                'user_id' => $user->id, // or $user->user_id if depending on relationship structure
                'research_id' => $submission->id,
                'title' => 'Result of Review Available',
                'message' => "Your Result of Review letter for \"{$submission->Study_Protocol_title}\" has been generated. You may now view it in your dashboard.",
                'type' => 'document_upload', // or 'status_update'
                'is_read' => false
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
        $submission = Research_title::with('researcher')->findOrFail($id);

        $userMessage = '';

        // Update status to Waiting for Revision for ALL review types
        $submission->Status = 'Waiting for Revision';
        $message = 'Status updated to Waiting for Revision.';
        $redirectRoute = 'admin.revisions';

        // Custom notification message
        $userMessage = "Your research protocol status has been updated to Waiting for Revision. Please check the recommendation letter and submit the necessary revisions based on the feedback provided.";

        $submission->save();

        // Notify the user
        UserNotification::create([
            'user_id' => $submission->researcher->user_id,
            'research_id' => $submission->id,
            'title' => 'Status Update: Waiting for Revision',
            'message' => $userMessage,
            'type' => 'status_update',
            'is_read' => false
        ]);

        return redirect()->route($redirectRoute)->with('success', $message);
    }


    // generateCertificate method removed as per request

    public function showGenerateCertificates($id)
    {
        $submission = Research_title::with(['researcher.user'])->findOrFail($id);
        $researcherName = trim(
            ($submission->researcher->user->first_name ?? '') . ' ' .
            ($submission->researcher->user->last_name ?? '')
        );
        return view('admin.generate_certificates', compact('submission', 'researcherName'));
    }


    public function generateCertificate(Request $request, $id)
    {
        $action = $request->input('action', 'generate');

        $rules = [];

        if ($action === 'preview_cert' || $action === 'generate') {
            $rules = array_merge($rules, [
                'cert_names'         => $action === 'generate' ? 'required|string|max:255' : 'nullable|string|max:255',
                'cert_title'         => $action === 'generate' ? 'required|string|max:500' : 'nullable|string|max:500',
                'cert_reo_code'      => 'nullable|string|max:100',
                'cert_reo_summary'   => 'nullable|string|max:2000',
            ]);
        }

        if ($action === 'preview_cover' || $action === 'generate') {
            $rules = array_merge($rules, [
                'cover_reo_code'        => 'nullable|string|max:100',
                'cover_version'         => 'nullable|string|max:50',
                'cover_title'           => $action === 'generate' ? 'required|string|max:500' : 'nullable|string|max:500',
                'cover_approved_period' => $action === 'generate' ? 'required|date' : 'nullable|date',
                'cover_expiry_date'     => $action === 'generate' ? 'required|date|after_or_equal:cover_approved_period' : 'nullable|date',
                'cover_researcher'      => $action === 'generate' ? 'required|string|max:255' : 'nullable|string|max:255',
            ]);
        }

        if ($action === 'generate') {
            $rules['pickup_date'] = 'required|date|after_or_equal:today';
        }

        $request->validate($rules);


        $submission = Research_title::with('researcher.user')->findOrFail($id);

        // Ensure output directory exists
        $outputDir = storage_path('app/public/certificates/generated');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0775, true);
        }

        $approvedFormatted = $request->has('cover_approved_period') ? Carbon::parse($request->cover_approved_period)->format('F j, Y') : '';
        $expiryFormatted   = $request->has('cover_expiry_date') ? Carbon::parse($request->cover_expiry_date)->format('F j, Y') : '';
        $formattedPickup   = $request->has('pickup_date') ? Carbon::parse($request->pickup_date)->format('F j, Y') : '';

        // ----------------------------------------------------------------
        // 1. Generate Cover Letter
        // ----------------------------------------------------------------
        if ($action === 'preview_cover' || $action === 'generate') {
        $coverTemplatePath = storage_path('app/public/certificates/Cover Letter.pdf');
        if (!file_exists($coverTemplatePath)) {
            return back()->with('error', 'Cover Letter template not found.');
        }

        $coverPdf = new \setasign\Fpdi\Tcpdf\Fpdi();
        $coverPdf->setSourceFile($coverTemplatePath);
        $coverTpl = $coverPdf->importPage(1);
        $size = $coverPdf->getTemplateSize($coverTpl);
        $coverPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $coverPdf->useTemplate($coverTpl, 0, 0, $size['width'], $size['height']);
        $coverPdf->SetAutoPageBreak(false);
        $coverPdf->SetFont('helvetica', '', 11);
        $coverPdf->SetTextColor(0, 0, 0);

        // REO Code
        if ($request->cover_reo_code) {
            $coverPdf->SetXY(67, 129);
            $coverPdf->Write(0, $request->cover_reo_code);
        }

        // Title
        $coverPdf->SetXY(67, 139);
        $coverPdf->MultiCell(90, 4, $request->cover_title);
        // Approved period
        $coverPdf->SetXY(67, 161);
        $coverPdf->Write(0, $approvedFormatted);

        // Version
        if ($request->cover_version) {
            $coverPdf->SetXY(67, 149); // Placing version under title
            $coverPdf->Write(0, $request->cover_version);
        }

        // Expiry date
        $coverPdf->SetXY(67, 165.5);
        $coverPdf->Write(0, $expiryFormatted);

        // Researcher
        $coverPdf->SetXY(67, 170);
        $coverPdf->MultiCell(90, 4, $request->cover_researcher);

        if ($action === 'preview_cover') {
            return response($coverPdf->Output('S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Preview_Cover_Letter.pdf"'
            ]);
        }

        $coverFilename   = 'Cover_Letter_' . $submission->id . '_' . time() . '.pdf';
        $coverOutputPath = $outputDir . '/' . $coverFilename;
        $coverPdf->Output('F', $coverOutputPath);

        researcher_files::create([
            'research_title_id' => $submission->id,
            'filename'          => 'Cover Letter of Approval',
            'filetype'          => 'Approval Letter',
            'filepath'          => 'storage/certificates/generated/' . $coverFilename,
        ]);
        }

        // ----------------------------------------------------------------
        // 2. Generate Certificate of Exemption
        // ----------------------------------------------------------------
        if ($action === 'preview_cert' || $action === 'generate') {
        $certTemplatePath = storage_path('app/public/certificates/2026-Certificate of Exemption template.pdf');
        if (!file_exists($certTemplatePath)) {
            return back()->with('error', 'Certificate of Exemption template not found.');
        }

        // Map TCPDF font cache to the pre-compiled directory to avoid on-the-fly generation issues
        if (!defined('K_PATH_FONTS')) {
            define('K_PATH_FONTS', storage_path('app/tcpdf_fonts/'));
        }

        $certPdf = new \setasign\Fpdi\Tcpdf\Fpdi();
        $certPdf->setSourceFile($certTemplatePath);
        $certTpl   = $certPdf->importPage(1);
        $certSize  = $certPdf->getTemplateSize($certTpl);
        $certPdf->AddPage($certSize['orientation'], [$certSize['width'], $certSize['height']]);
        $certPdf->useTemplate($certTpl, 0, 0, $certSize['width'], $certSize['height']);
        $certPdf->SetAutoPageBreak(false);
        $certPdf->SetTextColor(0, 0, 0);

        $w = $certSize['width'];

        // Names (researcher/s) — Nautilus Pompilius 18, centered, #6d412a
        $certPdf->SetTextColor(109, 65, 42); // #6d412a
        // Temporarily using helvetica since Nautilus PostScript OTF mapping fails in TCPDF
        $certPdf->SetFont('helvetica', '', 18);
        $certPdf->SetXY(30, 138);
        $certPdf->Cell($w - 60, 6, $request->cert_names, 0, 0, 'C');

        // Color for Title, Code, Summary: #2b1511
        $certPdf->SetTextColor(43, 21, 17);

        // Title — Colette 11.4, centered, multi-line
        $certPdf->SetFont('colette', '', 11.4);
        $certPdf->SetXY(30, 150);
        $certPdf->MultiCell($w - 60, 6, '"' . $request->cert_title . '"', 0, 'C', false, 1, null, null, true, 0, false, true, 0, 'T', false);

        // REO Code — Colette 12, centered
        if ($request->cert_reo_code) {
            $certPdf->SetFont('colette', '', 12);
            $certPdf->SetXY(30, 170);
            $certPdf->Cell($w - 60, 6, $request->cert_reo_code, 0, 0, 'C');
        }

        // REO Summary / scope of exemption — Montserrat 11, justified
        if ($request->cert_reo_summary) {
            $certPdf->SetFont('montserrat', '', 11);
            $certPdf->SetXY(30, 180);
            $certPdf->MultiCell($w - 60, 5, $request->cert_reo_summary, 0, 'J', false, 1, null, null, true, 0, false, true, 0, 'T', false);
        }

        if ($action === 'preview_cert') {
            return response($certPdf->Output('S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Preview_Certificate_of_Exemption.pdf"'
            ]);
        }

        $certFilename   = 'Certificate_' . $submission->id . '_' . time() . '.pdf';
        $certOutputPath = $outputDir . '/' . $certFilename;
        $certPdf->Output('F', $certOutputPath);

        researcher_files::create([
            'research_title_id' => $submission->id,
            'filename'          => 'Ethics Clearance Certificate',
            'filetype'          => 'certificate',
            'filepath'          => 'storage/certificates/generated/' . $certFilename,
        ]);
        }

        if ($action === 'generate') {
            // ----------------------------------------------------------------
            // 3. Appointment & Notification
            // ----------------------------------------------------------------
            $pickupDate = Carbon::parse($request->pickup_date);
            Appointment::create([
                'research_title_id' => $submission->id,
                'user_id'           => $submission->researcher->user_id,
                'appointment_date'  => $pickupDate->setTime(9, 0),
                'stage'             => 'Certificate Pickup',
            ]);

            UserNotification::create([
                'user_id'     => $submission->researcher->user_id,
                'research_id' => $submission->id,
                'title'       => 'Certification Documents Ready',
                'message'     => "Your Cover Letter of Approval and Research Ethics Clearance Certificate for \"{$submission->Study_Protocol_title}\" have been generated and are ready for pickup at the REO building on {$formattedPickup}.",
                'type'        => 'status_update',
                'is_read'     => false,
            ]);

            return redirect()
                ->route('admin.certifications')
                ->with('success', 'Certification documents generated and researcher notified successfully.');
        }
    }

    public function revisions(Request $request)
    {
        $query = Research_title::with(['researcher.user', 'revisionLogs.user', 'user']);

        // Default valid statuses for this page
        $defaultStatuses = ['Waiting for Revision', 'Revision Submitted', 'Corrections Submitted', 'Checking of Revisions', 'Panel Deliberation'];

        if ($request->filled('statuses') && is_array($request->statuses)) {
            // Intersect to ensure they only filter within the allowed Revisions statuses
            $filteredStatuses = array_intersect($request->statuses, $defaultStatuses);
            $query->whereIn('Status', !empty($filteredStatuses) ? $filteredStatuses : $defaultStatuses);
        } else {
            $query->whereIn('Status', $defaultStatuses);
        }

        if ($request->filled('review_types') && is_array($request->review_types)) {
            $query->whereIn('Review_Type', $request->review_types);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Study_Protocol_title', 'like', "%{$search}%")
                    ->orWhereHas('researcher.user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $sortBy = $request->input('sort_by', 'updated_at');
        if ($sortBy === 'Title') {
            $query->orderBy('Study_Protocol_title', 'asc');
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        $datas = $query->paginate(5);

        if ($request->ajax()) {
            $html = view('admin.partials.active_revisions_list', compact('datas'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.Revisions', compact('datas'));
    }

    public function certifications(Request $request)
    {
        $query = Research_title::with(['researcher.user', 'files', 'adminFiles', 'user'])
            ->where('Status', 'Approved');

        if ($request->filled('review_types') && is_array($request->review_types)) {
            $query->whereIn('Review_Type', $request->review_types);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Study_Protocol_title', 'like', "%{$search}%")
                    ->orWhereHas('researcher.user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $sortBy = $request->input('sort_by', 'updated_at');
        if ($sortBy === 'Title') {
            $query->orderBy('Study_Protocol_title', 'asc');
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        $datas = $query->paginate(5);

        if ($request->ajax()) {
            $html = view('admin.partials.active_certifications_list', compact('datas'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.certifications', compact('datas'));
    }
    public function meetings()
    {
        $upcomingMeetings = Meeting::where('meeting_date', '>=', now())
            ->orderBy('meeting_date', 'asc')
            ->get();

        $nextMeeting = $upcomingMeetings->first();

        // Fetch Upcoming Appointments (e.g., Panel Deliberation)
        $upcomingAppointments = Appointment::with('research')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date', 'asc')
            ->limit(5)
            ->get();

        // Fetch Recent Protocol Activities (Key Statuses)
        $recentActivities = Research_title::whereIn('Status', [
            'For Initial Review',
            'Modifications Required',
            'Waiting for Revision',
            'Approved',
            'Panel Deliberation'
        ])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.meetings.index', compact('upcomingMeetings', 'nextMeeting', 'upcomingAppointments', 'recentActivities'));
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
        $meeting = Meeting::with([
            'agendaItems' => function ($query) {
                $query->orderBy('order', 'asc');
            }
        ])->findOrFail($id);

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
            'content' => $request->input('content'),
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
            'content' => $request->input('content'),
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
