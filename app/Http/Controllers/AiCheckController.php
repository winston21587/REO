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
            'research_files.*' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max per file
        ]);

        $extractedTexts = [];
        foreach ($request->file('research_files') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $text = '';

            try {
                if ($extension === 'pdf') {
                    $popplerPath = base_path('resources\poppler-25.07.0\Library\bin\pdftotext.exe');

                    $text = (new Pdf($popplerPath))
                        ->setPdf($file->path())
                        ->text();

                    // Path to your Poppler executable
                    // $text = (new Pdf('resources\poppler-25.07.0\Library\bin\pdftotext.exe'))->setPdf($file->path())->text();
                    
                // } elseif (in_array($extension, ['doc', 'docx'])) {
                //     $phpWord = IOFactory::load($file->path());
                //     foreach ($phpWord->getSections() as $section) {
                //         foreach ($section->getElements() as $element) {
                //             if (method_exists($element, 'getText')) {
                //                 $text .= $element->getText() . ' ';
                //             }
                //         }
                //     }
                // }
                } elseif (in_array($extension, ['doc', 'docx'])) {
                    $text = $this->safeExtractWord($file->path());
                }


                $extractedTexts[] = "-- DOCUMENT: {$originalName} --\n{$text}";

            } catch (\Exception $e) {
                Log::error("Failed to extract text from {$originalName}: " . $e->getMessage());
                return response()->json(['error' => "Could not process file: {$originalName}. Ensure it is not corrupted."], 500);
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
            'HTTP-Referer' => config('app.url'),      // Uses http://reo.test from your .env file 
            'X-Title' => config('app.name'),          // Uses Laravel from your .env file 
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
        
        // Correctly parse the OpenAI-compatible response from OpenRouter
        // Correctly parse the OpenAI-compatible response from OpenRouter
        $content = $result['choices'][0]['message']['content'] ?? '';
        
        // Clean up markdown code blocks if present
        $content = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
        
        $aiFeedback = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Fallback if JSON parsing fails
            Log::error('AI JSON Parse Error: ' . json_last_error_msg() . ' Content: ' . $content);
            return response()->json(['error' => 'Failed to parse AI response. Please try again.'], 500);
        }

        return response()->json(['results' => $aiFeedback]);
    }

    private function getAiPromptTemplate(): string
    {
        return <<<PROMPT
**Persona:** You are an AI Research Compliance Assistant. Your function is to meticulously pre-screen research documents against a list of formatting and content requirements before they are formally submitted to a Research Ethics Office. You are detail-oriented, precise, and helpful.

**Task:** I will provide you with the content of several research documents. You must analyze EACH document ONLY against its specific requirements listed below and generate a compliance report. The file names will be provided.

**Document Requirements:**
1.  **Application Form (PDF):**
    * Must contain a clear indication of a signature (e.g., an electronic signature like "/s/ Researcher Name", a signature placeholder like "[SIGNATURE HERE]", or text confirming a signature is present).
2.  **Research Protocol/Proposal (PDF):**
    * Must show evidence of page numbers (e.g., "Page 1 of 10").
    * Must show evidence of line numbers (e.g., numbers at the beginning of paragraphs or lines).
3.  **Technical Review Clearance (PDF):**
    * Confirm its presence. No content check is needed.
4.  **Data Collection Instruments (PDF):**
    * Must show evidence of page numbers.
    * Must show evidence of line numbers.
5.  **Informed Consent/Assent (PDF):**
    * Must show evidence of page numbers.
    * Must show evidence of line numbers.
6.  **Curriculum Vitae of Researcher/s (PDF):**
    * Confirm its presence. No content check is needed.
7.  **Completed Study Protocol Assessment Form (Word):**
    * Scan the text for any fields marked with an asterisk (*) that appear to be empty or have placeholder text (e.g., "*Response: [enter text here]" or "*Name: ").
8.  **Completed Informed Consent Assessment Form (Word):**
    * Scan the text for any fields marked with an asterisk (*) that appear to be empty or have placeholder text.
9.  **Completed Exempt Review Assessment Form (Word):**
    * Scan the text for any fields marked with an asterisk (*) that appear to be empty or have placeholder text.
10. **Other documents (e.g., NCIP Clearance, MOA):**
    * Must contain a clear indication of a signature.

**Output Format:**
Return ONLY a valid JSON array of objects. Do not include any markdown formatting (like ```json). Each object must have the following keys:
- "document_name": The name of the file.
- "status": "pass" or "fail".
- "issues": A string describing the issues, or "All clear" if passed.

**Begin Analysis:** Here are the documents.
PROMPT;
    }
    public function analyzeProtocolType(Request $request, $id)
    {
        // 1. Find the relevant file
        // We look for "Study Protocol Assessment Form" in the researcher_files table for this research_id
        $fileRecord = \App\Models\researcher_files::where('research_title_id', $id)
                        ->where('filename', 'like', '%Study Protocol Assessment Form%') // Adjust keyword as needed
                        ->latest()
                        ->first();

        if (!$fileRecord) {
            return response()->json([
                'found' => false, 
                'message' => 'Study Protocol Assessment Form not found.'
            ]);
        }

        // 2. Extract Text
        $text = '';
        try {
            $path = storage_path('app/public/' . $fileRecord->file_path); // Adjust based on your storage config
            // If using local disk, might be: storage_path('app/' . $fileRecord->file_path) or public_path(...)
            // Assuming 'public' disk:
            if (!file_exists($path)) {
                 // Try relative to public path if storage link is set up
                 $path = public_path('storage/' . $fileRecord->file_path);
            }
            
            if (!file_exists($path)) {
                 // Fallback: try to find it via the model's path attribute directly if it's absolute or relative
                 $path = $fileRecord->file_path; 
            }


            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'pdf') {
                $popplerPath = base_path('resources\poppler-25.07.0\Library\bin\pdftotext.exe');
                $text = (new Pdf($popplerPath))->setPdf($path)->text();
            } elseif (in_array($extension, ['doc', 'docx'])) {
                $text = $this->safeExtractWord($path);
            }
        } catch (\Exception $e) {
            Log::error("AI Analysis Error: " . $e->getMessage());
            return response()->json([
                'found' => true, 
                'error' => 'Could not read file content.'
            ]);
        }

        // 3. Ask AI for Recommendation
        $prompt = "Analyze the following 'Study Protocol Assessment Form' content and determine the recommended type of review. 
        The options are: 'Expedited', 'Exempt', or 'Full Review'.
        
        Look for checkboxes or text indicating the recommendation.
        
        Return ONLY a JSON object with:
        - recommended_type: (string) One of the 3 options.
        - confidence: (string) High, Medium, or Low.
        - reasoning: (string) A short explanation (max 1 sentence).
        
        Content:
        " . substr($text, 0, 5000); // Limit text length

        // --- OPENROUTER CALL (Reusing your existing logic) ---
        $apiKey = config('services.openrouter.api_key');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'google/gemini-2.5-flash-lite',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        if ($response->failed()) {
            return response()->json(['found' => true, 'error' => 'AI Service Unavailable']);
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? '';
        $content = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
        $result = json_decode($content, true);

        return response()->json([
            'found' => true,
            'filename' => $fileRecord->filename,
            'suggestion' => $result
        ]);
    }
}
