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


        return view('admin.analytics', compact(
            'totalSubmissions', 
            'approvedCount', 
            'approvalRate', 
            'activeResearchers',
            'monthlyData',
            'doneCount',
            'activeCount',
            'pendingCount',
            'completionRate'
        ));
    }

    public function index($request)
    {
        return view('admin.analytics');
    }
public function applications(Request $request)
    {
        $query = Research_title::with(['author', 'files']);

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
                $newStatus = 'For Initial Review';
                $submission->Status = $newStatus;
                $submission->save();
                $appointment = Appointment::create([
                    'research_title_id' => $submission->id,
                    'user_id' => $submission->user_id,
                    'appointment_date' => $request->appointment_date,
                    'stage' => 'Initial Review',
                ]);
                $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
                $message = "Your submission document check is Complete. We have set your Initial Review Appointment on: {$dateFormatted}.";
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
            $submission->Status = 'Under Review'; // Moving it forward
            $submission->save();

            // Create Appointment
            Appointment::create([
                'research_title_id' => $submission->id,
                'user_id' => $submission->user_id,
                'appointment_date' => $request->appointment_date,
                'stage' => $request->review_type, // e.g., 'Expedited Review'
            ]);

            $dateFormatted = Carbon::parse($request->appointment_date)->format('F j, Y');
            $message = "Your research has been classified as **{$request->review_type}**.\n";
            $message .= "An appointment/deadline has been set for: {$dateFormatted}.";

            if ($request->remarks) {
                $message .= "\n\nRemarks: " . $request->remarks;
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
            'title' => 'Submission Status Update',
            'message' => $message,
            'type' => 'info',
            'is_read' => false
        ]);

        return response()->json(['success' => true, 'message' => 'Status and Review Type updated successfully']);
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
            'filepath' => $path, // Storing the path
            'filetype' => 'Result of Review (Admin Generated)',
        ]);

        // E. Return the view for the browser to print
        return response($htmlContent);
    }
    // 3. Recommendation Letter Feature
    public function showRecommendationLetterForm($id)
    {
        $submission = Research_title::with(['author', 'files'])->findOrFail($id);
        $hasLetter = $submission->files->where('filetype', 'Result of Review (Admin Generated)')->isNotEmpty();
        
        return view('admin.recommendation_letter.form', compact('submission', 'hasLetter'));
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
                'filetype' => 'recommendation letter',
            ]);

            return redirect()->route('admin.applications')->with('success', 'Recommendation Letter generated and saved successfully.');
        }
    }

    public function checkFileStatus($id)
    {
        $submission = Research_title::with('files')->findOrFail($id);
        $hasLetter = $submission->files->where('filetype', 'recommendation letter')->isNotEmpty();
        
        return response()->json([
            'has_recommendation_letter' => $hasLetter
        ]);
    }
}
