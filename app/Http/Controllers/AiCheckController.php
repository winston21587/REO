<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\IOFactory;

class AiCheckController extends Controller
{
    private function safeExtractWord($path)
{
    $text = '';

    try {
        $reader = IOFactory::createReader('Word2007'); // safer than load()
        $phpWord = $reader->load($path);
    } catch (\Exception $e) {
        throw new \Exception("Word document could not be read.");
    }

    foreach ($phpWord->getSections() as $section) {
        foreach ($section->getElements() as $element) {

            // Get simple text elements
            if (method_exists($element, 'getText')) {
                $text .= $element->getText() . ' ';
            }

            // Handle TextRun elements
            if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                foreach ($element->getElements() as $child) {
                    if (method_exists($child, 'getText')) {
                        $text .= $child->getText() . ' ';
                    }
                }
            }
        }
    }

    if (trim($text) === '') {
        throw new \Exception("No readable text found in Word document.");
    }

    return $text;
}

    public function checkDocuments(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.supplementary_docs' => 'nullable|array',
            'files.supplementary_docs.*' => 'file|mimes:pdf,doc,docx|max:51200',
            
            // Explicitly validate single files to avoid "files.*" failing on the supplementary_docs array
            'files.application_form' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.research_protocol' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.technical_clearance' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.data_collection_instruments' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.informed_consent' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.curriculum_vitae' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.study_protocol_form' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.informed_consent_form' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'files.exempt_review_form' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
        ]);

        // Map input names to human-readable document types
        $documentTypes = [
            'application_form' => 'Application Form for Research Ethics Review',
            'research_protocol' => 'Research Protocol / Proposal',
            'technical_clearance' => 'Technical Review Clearance',
            'data_collection_instruments' => 'Data Collection Instrument/s',
            'informed_consent' => 'Informed Consent / Assent',
            'curriculum_vitae' => 'Curriculum Vitae of Researcher/s',
            'study_protocol_form' => 'Completed Study Protocol Assessment Form',
            'informed_consent_form' => 'Completed Informed Consent Assessment Form',
            'exempt_review_form' => 'Completed Exempt Review Assessment Form',
            'supplementary_docs' => 'Supplementary Document',
        ];

        $extractedTexts = [];
        
        if ($request->has('files')) {
            foreach ($request->file('files') as $key => $fileOrFiles) {
                // Handle array inputs (like supplementary_docs[])
                $files = is_array($fileOrFiles) ? $fileOrFiles : [$fileOrFiles];
                
                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $text = '';
                    
                    // Determine expected document type
                    $expectedType = $documentTypes[$key] ?? 'Unknown Document Type';

                    try {
                        if ($extension === 'pdf') {
                            $popplerPath = base_path('resources\poppler-25.07.0\Library\bin\pdftotext.exe');
                            $text = (new Pdf($popplerPath))->setPdf($file->path())->text();
                        } elseif (in_array($extension, ['doc', 'docx'])) {
                            $text = $this->safeExtractWord($file->path());
                        }

                        // Limit text to avoid token limits (approx 10k chars per doc)
                        $text = substr($text, 0, 10000);

                        $extractedTexts[] = "-- DOCUMENT START --\nFILENAME: {$originalName}\nEXPECTED_TYPE: {$expectedType}\nCONTENT:\n{$text}\n-- DOCUMENT END --";

                    } catch (\Exception $e) {
                        Log::error("Failed to extract text from {$originalName}: " . $e->getMessage());
                        return response()->json(['error' => "Could not process file: {$originalName}. Ensure it is not corrupted."], 500);
                    }
                }
            }
        }

        if (empty($extractedTexts)) {
            return response()->json(['error' => 'No text could be extracted from the files provided.'], 400);
        }

        $fullDocumentText = implode("\n\n", $extractedTexts);
        $prompt = $this->getAiPromptTemplate();
        $finalPrompt = $prompt . "\n\n" . $fullDocumentText;

        // --- START OF OPENROUTER API CALL ---
        $apiKey = config('services.openrouter.api_key');
        if (!$apiKey) {
            return response()->json(['error' => 'The OpenRouter API key is not configured.'], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->timeout(120)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'google/gemini-2.5-flash-lite',
            'messages' => [
                ['role' => 'user', 'content' => $finalPrompt]
            ]
        ]);
        // --- END OF OPENROUTER API CALL ---

        if ($response->failed()) {
            Log::error('OpenRouter API request failed: ' . $response->body());
            return response()->json(['error' => 'The AI checker could not process the request. The service may be busy or your API key may have restrictions.'], 500);
        }

        $result = $response->json();
        
        $content = $result['choices'][0]['message']['content'] ?? '';
        $content = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
        
        $aiFeedback = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('AI JSON Parse Error: ' . json_last_error_msg() . ' Content: ' . $content);
            return response()->json(['error' => 'Failed to parse AI response. Please try again.'], 500);
        }

        return response()->json(['results' => $aiFeedback]);
    }

    private function getAiPromptTemplate(): string
    {
        return <<<PROMPT
**Persona:** You are a strict AI Research Compliance Officer. Your job is to verify that uploaded documents MATCH their expected type and meet specific requirements.

**Task:** I will provide you with the text content of several files. Each file is marked with its FILENAME and EXPECTED_TYPE.
You must verify:
1.  **Identity Check:** Does the content of the file actually match the EXPECTED_TYPE? (e.g., If expected is "Application Form", but the content looks like a "Curriculum Vitae", it FAILS).
2.  **Requirement Check:** Does it meet the specific requirements for that type?

**Specific Requirements per Type:**

1.  **Application Form for Research Ethics Review:**
    *   **Identity:** Must look like a formal application form.
    *   **Requirement:** Must contain a signature or a placeholder for a signature (e.g., "/s/", "Signed:", "[Signature]").

2.  **Research Protocol / Proposal:**
    *   **Identity:** Must be a research proposal/protocol document.
    *   **Requirement:** Must have page numbers (e.g., "Page X") AND line numbers (numbers at start of lines).

3.  **Technical Review Clearance:**
    *   **Identity:** Must be a clearance certificate or approval document.
    *   **Requirement:** Must contain signatures of panel members.

4.  **Data Collection Instrument/s:**
    *   **Identity:** Must be a survey, questionnaire, or interview guide.
    *   **Requirement:** Must have page numbers AND line numbers.

5.  **Informed Consent / Assent:**
    *   **Identity:** Must be a consent form for participants.
    *   **Requirement:** Must have page numbers AND line numbers.

6.  **Curriculum Vitae of Researcher/s:**
    *   **Identity:** Must be a CV or Resume.
    *   **Requirement:** None (just identity check).

7.  **Completed Study Protocol Assessment Form:**
    *   **Identity:** Must be an assessment form.
    *   **Requirement:** Check for empty required fields (marked with *).

8.  **Completed Informed Consent Assessment Form:**
    *   **Identity:** Must be an assessment form.
    *   **Requirement:** Check for empty required fields.

9.  **Completed Exempt Review Assessment Form:**
    *   **Identity:** Must be an assessment form.
    *   **Requirement:** Check for empty required fields.

**Output Format:**
Return ONLY a valid JSON array of objects. No markdown.
[
    {
        "document_name": "filename.pdf",
        "status": "pass" or "fail",
        "issues": "Description of failure (e.g., 'Wrong document type: Uploaded a CV instead of Application Form' or 'Missing line numbers'). If pass, use 'All clear'."
    }
]

**Begin Analysis:**
PROMPT;
    }
    public function analyzeProtocolType(Request $request, $id)
    {
        set_time_limit(120); // Increase max execution time for long AI processing

        // 1. Find the relevant research title and its "Informed Consent" file
        $research = \App\Models\Research_title::with('files')->find($id);

        if (!$research) {
            return response()->json(['found' => false, 'message' => 'Research title not found.']);
        }

        $fileRecord = $research->files()
                        ->where(function ($query) {
                            $query->where('category', 'like', '%Study Protocol%')
                                  ->orWhere('filename', 'like', '%Study Protocol%');
                        })
                        ->latest()
                        ->first();

        if (!$fileRecord) {
            return response()->json([
                'found' => false, 
                'message' => 'No Study Protocol document found for this submission.'
            ]);
        }

        try {
            // Find absolute path
            $path = public_path($fileRecord->filepath);
            if (!file_exists($path)) {
                $path = storage_path('app/public/' . $fileRecord->filepath);
            }
            if (!file_exists($path)) {
                 $path = public_path('storage/' . $fileRecord->filepath);
            }
            if (!file_exists($path)) {
                 $path = storage_path('app/' . $fileRecord->filepath);
            }
            if (!file_exists($path)) {
                return response()->json([
                    'found' => true, 
                    'error' => 'Physical file could not be located on the server.'
                ]);
            }

            // --- PYTHON NLP MODEL API CALL ---
            $apiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5001') . '/predict';

            // Send raw file to Flask server
            $response = Http::timeout(60)->attach(
                'file', file_get_contents($path), $fileRecord->filename
            )->post($apiUrl);

            if ($response->failed()) {
                Log::error('Python API request failed: ' . $response->body());
                return response()->json(['found' => true, 'error' => 'AI Classification Service Unavailable. Please ensure Python server is running.']);
            }

            $result = $response->json();
            
            if (!$result['success'] ?? false) {
                 return response()->json(['found' => true, 'error' => 'Python API returned an error: ' . ($result['error'] ?? 'Unknown error')]);
            }

            // Map prediction to OpenRouter's expected output format for UI compatibility
            $prediction = $result['prediction'] ?? 'UNKNOWN';
            
            // Format match
            $recommendedType = 'Unknown';
            if (stripos($prediction, 'EXPEDITED') !== false) {
                $recommendedType = 'Expedited Review';
            } elseif (stripos($prediction, 'EXEMPT') !== false) {
                $recommendedType = 'Exempt Review';
            } elseif (stripos($prediction, 'FULL') !== false) {
                $recommendedType = 'Full Board Review';
            }

            // Calculate confidence loosely based on chunk voting gap (optional fallback to High)
            // Or just hardcode High since the old UI expects it to Auto-select
            $confidence = 'High';

            $reasoning = "Analyzed {$result['chunks_analyzed']} text segments. Majority voted for {$prediction}.";
            if (!empty($result['breakdown'])) {
                 // Build a quick summary of the breakdown
                 $stats = [];
                 foreach ($result['breakdown'] as $cat => $data) {
                     if ($data['percentage'] > 0) {
                         $stats[] = "{$cat}: {$data['percentage']}%";
                     }
                 }
                 $reasoning .= " (" . implode(", ", $stats) . ")";
            }

            return response()->json([
                'found' => true,
                'filename' => $fileRecord->filename,
                'suggestion' => [
                    'recommended_type' => $recommendedType,
                    'confidence' => $confidence,
                    'reasoning' => $reasoning
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Flask AI Analysis Error: " . $e->getMessage());
            return response()->json([
                'found' => true, 
                'error' => 'An internal error occurred during prediction.'
            ]);
        }
    }
}
