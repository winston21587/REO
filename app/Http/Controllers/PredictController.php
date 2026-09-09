<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use LucianoTonet\GroqLaravel\Facades\Groq;
use App\Models\User;
use App\Models\Reviewer;

class PredictController extends Controller
{
    /**
     * Use OpenRouter / Groq API for IRB classification.
     */
    public function predict(Request $request)
    {
        @set_time_limit(120);
        @ini_set('max_execution_time', '120');

        $request->validate([
            'text' => 'required|string',
            'protocol_id' => 'nullable|integer',
        ]);

        $title = $request->text;
        $protocolId = $request->input('protocol_id');
        $protocolMetadata = [];
        $contextLines = [];

        if ($protocolId) {
            $protocol = \App\Models\Research_title::with(['adminFiles', 'files'])->find($protocolId);
            if ($protocol) {
                if (empty($title) || $title === 'Loading...') {
                    $title = $protocol->Study_Protocol_title;
                }

                if (!empty($protocol->Research_Category)) {
                    $protocolMetadata['category'] = $protocol->Research_Category;
                    $contextLines[] = "- Research Category: " . $protocol->Research_Category;
                }
                if (!empty($protocol->project_type)) {
                    $protocolMetadata['project_type'] = $protocol->project_type;
                    $contextLines[] = "- Project Type: " . $protocol->project_type;
                }
                if (!empty($protocol->course_type)) {
                    $protocolMetadata['course_type'] = $protocol->course_type;
                    $contextLines[] = "- Academic Level / Course: " . $protocol->course_type;
                }

                $files = $protocol->adminFiles->isNotEmpty() ? $protocol->adminFiles : $protocol->files;
                if ($files && $files->isNotEmpty()) {
                    $docCategories = $files->map(function ($f) {
                        return trim($f->category ?: pathinfo($f->filename, PATHINFO_FILENAME));
                    })->filter()->unique()->values()->all();

                    if (!empty($docCategories)) {
                        $protocolMetadata['attached_documents'] = $docCategories;
                        $contextLines[] = "- Submitted Protocol Documents: " . implode(', ', $docCategories);

                        $hasConsent = collect($docCategories)->contains(function ($cat) {
                            return stripos($cat, 'consent') !== false || stripos($cat, 'icf') !== false;
                        });
                        $protocolMetadata['has_consent_form'] = $hasConsent;
                        $contextLines[] = "- Human Informed Consent Form (FR.005) Present: " . ($hasConsent ? "YES (Direct human participant interaction confirmed)" : "NO / Not attached");
                    }
                }
            }
        }

        $contextStr = "Research Title: \"$title\"\n";
        if (!empty($contextLines)) {
            $contextStr .= "Protocol Context & Submission Metadata (from WMSU REOC Submission):\n" . implode("\n", $contextLines) . "\n";
        }
        
        $prompt = "Evaluate and classify the research protocol into EXACTLY ONE WMSU REOC Review Type pursuant to the Philippine National Ethical Guidelines for Research Involving Human Participants (NEGRIHP 2022) and the WMSU REOC Standard Operating Procedures Manual (SOP 04, SOP 05, SOP 06).\n\n"
            . "WMSU REOC CRITERIA & PATHWAYS:\n"
            . "1. EXEMPT (SOP 04 - Exempt from Review):\n"
            . "   - Protocols with no human participants, OR studies not involving more than minimal risk/harm.\n"
            . "   - Educational evaluations, curriculum analysis, institutional quality assurance, consumer acceptability, public health surveillance without individual identifiers.\n"
            . "   - Surveys, interviews, or observations of public behavior where responses do NOT place participants at legal, financial, or reputational liability, AND identity cannot be readily ascertained directly or through linked identifiers.\n"
            . "   - Secondary analysis of publicly available data or archived unidentifiable records.\n"
            . "   - Participants do NOT belong to vulnerable groups and no vulnerability issues arise.\n\n"
            . "2. EXPEDITED (SOP 05 - Expedited Review):\n"
            . "   - Research involving human participants that entails NO MORE than MINIMAL RISK.\n"
            . "   - Participants do NOT belong to vulnerable groups, and study procedures do NOT generate vulnerability.\n"
            . "   - Standard non-invasive biological/physiological data collection (e.g., blood pressure, saliva, hair, non-invasive imaging, physical fitness tests, routine clinical measurements without added hazard).\n"
            . "   - Primary data collection with human respondents (surveys, interviews, focus groups) where identifiable private data is collected but risk remains minimal.\n\n"
            . "3. FULL BOARD (SOP 06 - Full Review):\n"
            . "   - Research that entails MORE THAN MINIMAL RISK to participants.\n"
            . "   - Participants BELONG TO VULNERABLE GROUPS (e.g., children/minors, pregnant women with fetal risk, prisoners/inmates, indigenous cultural communities, persons with mental/cognitive disabilities, victims of trauma/abuse, institutionalized persons, severely impoverished/marginalized populations).\n"
            . "   - Study procedures GENERATE VULNERABILITY (e.g., clinical trials, experimental drugs/devices, invasive medical procedures, surgical/biopsy interventions, highly sensitive topics like HIV/STDs, domestic violence, illicit drug use, suicide, criminal acts).\n\n"
            . $contextStr . "\n"
            . "Respond with ONLY the category name (EXEMPT, EXPEDITED, or FULL BOARD) followed by a colon and a concise justification citing the risk level, participant vulnerability, or procedures according to NEGRIHP 2022 / WMSU REOC SOP guidelines. Keep reasoning brief and concise.\n"
            . "Category:";

        $openRouterKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY'));
        $openRouterModel = config('services.openrouter.model', env('OPENROUTER_MODEL', 'nex-agi/nex-n2.5-pro:free'));
        $openRouterUrl = config('services.openrouter.url', 'https://openrouter.ai/api/v1/chat/completions');

        try {
            // 1. Primary engine: OpenRouter
            if (!empty($openRouterKey)) {
                Log::info('Attempting OpenRouter AI Prediction for: ' . $title);

                $response = Http::timeout(25)->withHeaders([
                    'Authorization' => 'Bearer ' . $openRouterKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => config('app.url', 'http://reo.test'),
                    'X-Title' => config('app.name', 'WMSU REO'),
                ])->post($openRouterUrl, [
                    'model' => $openRouterModel,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are the AI Ethics Review Specialist for the Western Mindanao State University Research Ethics Oversight Committee (WMSU REOC). You classify research protocols into EXEMPT, EXPEDITED, or FULL BOARD review types strictly in accordance with the Philippine National Ethical Guidelines for Research Involving Human Participants (NEGRIHP 2022) and the WMSU REOC SOP Manual (SOP 04, SOP 05, SOP 06). Always respond with EXACTLY the category name followed by a colon and a clear ethical rationale.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'reasoning' => ['enabled' => true],
                    'temperature' => 0.1,
                    'max_tokens' => 450,
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $choice = $json['choices'][0]['message'] ?? [];
                    $rawOutput = trim($choice['content'] ?? '');
                    $reasoning = $choice['reasoning'] ?? ($choice['reasoning_details'] ?? null);
                    if (is_array($reasoning)) {
                        $reasoning = json_encode($reasoning, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    }
                    Log::info('OpenRouter Prediction Response: ' . $rawOutput);

                    $label = $this->extractLabelFromOutput($rawOutput ?: ($reasoning ?? ''));
                    $reasonText = $this->extractReasonFromOutput($rawOutput);
                    
                    if (empty($reasonText) && !empty($reasoning)) {
                        $reasonText = $this->extractReasonFromOutput($reasoning);
                    }
                    if (empty($reasonText)) {
                        $reasonText = "Evaluated pursuant to WMSU REOC SOP guidelines based on protocol metadata and risk indicators.";
                    }

                    return response()->json([
                        'success' => true,
                        'label' => $label,
                        'raw_prediction' => $rawOutput,
                        'reason' => $reasonText,
                        'reasoning' => $reasoning,
                        'provider' => 'OpenRouter',
                        'model' => $openRouterModel,
                        'metadata' => $protocolMetadata,
                    ]);
                } else {
                    Log::warning('OpenRouter API returned error: ' . $response->status() . ' - ' . $response->body());
                }
            }

            // 2. Secondary fallback: Groq (if configured)
            $groqKey = env('GROQ_API_KEY');
            if (!empty($groqKey)) {
                Log::info('Attempting Groq AI Prediction fallback for: ' . $title);

                $response = Groq::chat()->completions()->create([
                    'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are the AI Ethics Review Specialist for the Western Mindanao State University Research Ethics Oversight Committee (WMSU REOC). You classify research protocols into EXEMPT, EXPEDITED, or FULL BOARD review types strictly in accordance with the Philippine National Ethical Guidelines for Research Involving Human Participants (NEGRIHP 2022) and the WMSU REOC SOP Manual (SOP 04, SOP 05, SOP 06). Always respond with EXACTLY the category name followed by a colon and a clear ethical rationale.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.1,
                    'max_tokens' => 150,
                ]);

                $rawOutput = $response['choices'][0]['message']['content'] ?? '';
                $label = $this->extractLabelFromOutput($rawOutput);
                $reasonText = $this->extractReasonFromOutput($rawOutput);

                return response()->json([
                    'success' => true,
                    'label' => $label,
                    'raw_prediction' => $rawOutput,
                    'reason' => $reasonText,
                    'provider' => 'Groq',
                    'metadata' => $protocolMetadata,
                ]);
            }

            // 3. Resilient heuristic fallback
            $label = $this->heuristicPredict($title, $protocolMetadata);
            return response()->json([
                'success' => true,
                'label' => $label,
                'raw_prediction' => $label . ': Heuristic classification based on keyword indicators.',
                'reason' => 'Heuristic classification based on protocol title indicators.',
                'provider' => 'Heuristic',
                'metadata' => $protocolMetadata,
            ]);

        } catch (\Exception $e) {
            Log::error('AI Prediction Error: ' . $e->getMessage());
            
            $fallbackLabel = $this->heuristicPredict($title, $protocolMetadata);
            return response()->json([
                'success' => true,
                'label' => $fallbackLabel,
                'raw_prediction' => 'Fallback: ' . $e->getMessage(),
                'reason' => 'Estimated based on WMSU REOC SOP guidelines and protocol keywords.',
                'fallback' => true,
                'provider' => 'Heuristic Fallback',
                'model' => 'REOC Guideline Engine',
                'metadata' => $protocolMetadata,
            ]);
        }
    }

    private function extractLabelFromOutput($rawOutput)
    {
        // Look for category at the beginning of the response
        if (preg_match('/^(EXEMPT|EXPEDITED|FULL BOARD)/i', trim($rawOutput), $matches)) {
            $label = strtoupper($matches[1]);
            if ($label === 'FULL BOARD') return 'Full Board Review';
            if ($label === 'EXPEDITED') return 'Expedited Review';
            if ($label === 'EXEMPT') return 'Exempt Review';
        }
        
        // Search anywhere in the text
        if (preg_match('/\b(EXEMPT|EXPEDITED|FULL BOARD)\b/i', $rawOutput, $matches)) {
            $label = strtoupper($matches[1]);
            if ($label === 'FULL BOARD') return 'Full Board Review';
            if ($label === 'EXPEDITED') return 'Expedited Review';
            if ($label === 'EXEMPT') return 'Exempt Review';
        }
        
        // Fallback keyword search
        if (stripos($rawOutput, 'exempt') !== false) return 'Exempt Review';
        if (stripos($rawOutput, 'expedited') !== false) return 'Expedited Review';
        if (stripos($rawOutput, 'full board') !== false) return 'Full Board Review';
        
        return 'Expedited Review';
    }

    private function extractReasonFromOutput($rawOutput)
    {
        if (empty($rawOutput)) return '';
        $clean = trim($rawOutput);

        // Match standard format: "EXEMPT: <reason>" or "**EXEMPT**: <reason>"
        if (preg_match('/(?:\*\*|#)*(?:EXEMPT|EXPEDITED|FULL BOARD)(?:\*\*|#)*\s*:\s*([^\n\r]+)/i', $clean, $matches)) {
            $reason = trim($matches[1]);
            return preg_replace('/[*_`]+$/', '', $reason);
        }

        // If it's a long reasoning stream, extract a concise summary sentence
        if (strlen($clean) > 250) {
            if (preg_match('/(?:therefore|in conclusion|hence|conservative classification|recommendation is to classify as)\s*([^.\n]+\.)/i', $clean, $matches)) {
                return ucfirst(trim($matches[0]));
            }
            if (preg_match('/^([^.\n]{20,200}\.)/s', $clean, $matches)) {
                return trim($matches[1]);
            }
            return "Evaluated under WMSU REOC SOP guidelines and NEGRIHP 2022 standards based on protocol risk and participant vulnerability.";
        }

        return $clean;
    }

    private function heuristicPredict($title, $protocolMetadata = [])
    {
        $lower = strtolower($title);
        // Vulnerable groups or procedures generating vulnerability (SOP 06 / NEGRIHP 2022)
        if (preg_match('/\b(child|children|pediatric|minor|minors|infant|infants|prisoner|prisoners|prison|inmate|inmates|indigenous|ip|tribal|pregnant|fetus|fetal|mental health|psychiatric|cognitive|impaired|disability|disabled|hiv|aids|trauma|abuse|violence|suicide|addiction|illicit|drug use|clinical trial|experimental drug|novel drug|therapy|intervention|surgery|surgical|biopsy|catheter|invasive)\b/i', $lower)) {
            return 'Full Board Review';
        }
        // Minimal risk with human interaction / physiological measures (SOP 05 / NEGRIHP 2022)
        if (preg_match('/\b(patient|patients|nurses|teachers|students|employees|farmers|consumers|respondents|participants|survey|interview|questionnaire|focus group|perception|lived experience|attitude|satisfaction|blood pressure|cortisol|saliva|biomarker|eeg|ekg|mri|ultrasound|exercise|fitness|anthropometric)\b/i', $lower)) {
            return 'Expedited Review';
        }
        // If human informed consent form is explicitly attached, human interaction is present -> Expedited minimum
        if (!empty($protocolMetadata['has_consent_form'])) {
            return 'Expedited Review';
        }
        // Non-human, secondary data, curriculum, public evaluation (SOP 04 / NEGRIHP 2022)
        return 'Exempt Review';
    }

    public function save(Request $request)
    {
        $request->validate([
            'protocol_id' => 'required|integer|exists:research_title_information,id',
            'suggested_review_type' => 'required|string',
        ]);

        $protocol = \App\Models\Research_title::find($request->protocol_id);
        $protocol->ai_suggested_review_type = $request->suggested_review_type;
        $protocol->save();

        return response()->json([
            'success' => true,
            'message' => 'Prediction saved successfully.'
        ]);
    }







    /**
 * Suggest the best reviewer for a research title using AI.
 */
    public function suggestReviewer(Request $request)
    {
        @set_time_limit(120);
        @ini_set('max_execution_time', '120');

        $request->validate([
            'title' => 'required|string',
        ]);

        $openRouterKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY'));
        $openRouterModel = config('services.openrouter.model', env('OPENROUTER_MODEL', 'nex-agi/nex-n2.5-pro:free'));
        $openRouterUrl = config('services.openrouter.url', 'https://openrouter.ai/api/v1/chat/completions');
        $groqKey = env('GROQ_API_KEY');

        $title = $request->title;
        
        // Fetch all reviewers from database with their expertise
        $reviewers = User::whereHas('reviewer')->with('reviewer')->get();
        
        if ($reviewers->isEmpty()) {
            return response()->json([
                'success' => false, 
                'message' => 'No reviewers found in the system'
            ], 404);
        }

        // Format reviewers list with ACTUAL expertise from database
        $reviewerList = [];
        foreach ($reviewers as $reviewer) {
            $expertise = $reviewer->reviewer->expertise ?? [];
            
            // Handle different formats of expertise
            if (is_array($expertise)) {
                $expertiseStr = implode(', ', $expertise);
            } elseif (is_string($expertise)) {
                $expertiseStr = $expertise;
            } else {
                $expertiseStr = $reviewer->college ?? 'General Research Review';
            }
            
            // Clean up empty expertise
            if (empty(trim($expertiseStr))) {
                $expertiseStr = $reviewer->college ?? 'General Research Review';
            }
            
            $reviewerList[] = [
                'id' => $reviewer->id,
                'name' => trim($reviewer->first_name . ' ' . $reviewer->last_name),
                'first_name' => $reviewer->first_name,
                'last_name' => $reviewer->last_name,
                'expertise' => $expertiseStr
            ];
        }
        
        // Build prompt with ACTUAL expertise from database
        $reviewerOptions = "";
        foreach ($reviewerList as $index => $reviewer) {
            $reviewerOptions .= ($index + 1) . ". " . $reviewer['name'] . "\n   Expertise: " . $reviewer['expertise'] . "\n\n";
        }
        
        $systemPrompt = "You are the REOC Reviewer Assignment Coordinator for the Western Mindanao State University Research Ethics Oversight Committee (WMSU REOC).\n\n"
            . "Pursuant to WMSU REOC SOP 05 (Expedited Review) and SOP 06 (Full Review), the assignment of primary reviewers must be based on the alignment of the research protocol topic with the actual field of expertise of the reviewers to ensure appropriate and knowledgeable evaluation.\n\n"
            . "Available REOC Reviewers and their ACTUAL EXPERTISE:\n{$reviewerOptions}\n"
            . "INSTRUCTIONS:\n"
            . "1. Read the research title carefully.\n"
            . "2. Compare the research topic with each reviewer's field of expertise.\n"
            . "3. Choose the reviewer whose expertise MOST CLOSELY aligns with the research subject matter.\n"
            . "4. Respond with ONLY the reviewer's FULL NAME exactly as listed above.\n"
            . "5. Do not include extra explanation or punctuation.\n\n"
            . "Valid reviewer names: " . implode(', ', array_column($reviewerList, 'name'));
        
        $userPrompt = "Research Title: \"$title\"\n\n"
            . "Based on matching the research topic with the reviewer's ACTUAL EXPERTISE, which reviewer should be assigned?\n\n"
            . "Reviewer Name:";

        try {
            $response = null;

            if (!empty($openRouterKey)) {
                $response = Http::timeout(25)->withHeaders([
                    'Authorization' => 'Bearer ' . $openRouterKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => config('app.url', 'http://reo.test'),
                    'X-Title' => config('app.name', 'WMSU REO'),
                ])->post($openRouterUrl, [
                    'model' => $openRouterModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt]
                    ],
                    'reasoning' => ['enabled' => true],
                    'temperature' => 0.1,
                    'max_tokens' => 80,
                ]);
            } elseif (!empty($groqKey)) {
                $response = Http::timeout(25)->withHeaders([
                    'Authorization' => 'Bearer ' . $groqKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt]
                    ],
                    'temperature' => 0.1,
                    'max_tokens' => 80,
                ]);
            }

            if ($response && $response->successful()) {
                $content = $response->json();
                $predictedName = trim($content['choices'][0]['message']['content'] ?? '');
                Log::info('AI Suggested Reviewer: ' . $predictedName);
                
                // Find matching reviewer by name with flexible matching
                $matchedReviewer = null;
                
                // Try exact match first
                foreach ($reviewerList as $reviewer) {
                    if (strtolower(trim($reviewer['name'])) === strtolower(trim($predictedName))) {
                        $matchedReviewer = $reviewer;
                        Log::info('Exact match found: ' . $reviewer['name']);
                        break;
                    }
                }
                
                // Try partial match
                if (!$matchedReviewer) {
                    foreach ($reviewerList as $reviewer) {
                        if (stripos($reviewer['name'], $predictedName) !== false || 
                            stripos($predictedName, $reviewer['name']) !== false) {
                            $matchedReviewer = $reviewer;
                            Log::info('Partial match found: ' . $reviewer['name']);
                            break;
                        }
                    }
                }
                
                // Try by first name or last name
                if (!$matchedReviewer) {
                    $predictedLower = strtolower($predictedName);
                    foreach ($reviewerList as $reviewer) {
                        if (strtolower($reviewer['first_name']) === $predictedLower ||
                            strtolower($reviewer['last_name']) === $predictedLower) {
                            $matchedReviewer = $reviewer;
                            Log::info('First/last name match found: ' . $reviewer['name']);
                            break;
                        }
                    }
                }
                
                // If still no match, use expertise-based matching
                if (!$matchedReviewer) {
                    Log::info('No name match, using expertise matching from database');
                    $matchedReviewer = $this->matchByExpertise($title, $reviewerList);
                }
                
                return response()->json([
                    'success' => true,
                    'suggested_reviewer_id' => $matchedReviewer['id'],
                    'suggested_reviewer_name' => $matchedReviewer['name'],
                    'suggested_reviewer_expertise' => $matchedReviewer['expertise'],
                    'ai_raw_prediction' => $predictedName
                ]);
            }

            Log::error('Reviewer suggestion API failed: ' . $response->status() . ' - ' . $response->body());
            
            // Fallback: use expertise matching from database
            $fallbackReviewer = $this->matchByExpertise($title, $reviewerList);
            
            return response()->json([
                'success' => true,
                'suggested_reviewer_id' => $fallbackReviewer['id'],
                'suggested_reviewer_name' => $fallbackReviewer['name'],
                'suggested_reviewer_expertise' => $fallbackReviewer['expertise'],
                'ai_raw_prediction' => 'Fallback expertise match'
            ]);

        } catch (\Exception $e) {
            Log::error('Reviewer suggestion error: ' . $e->getMessage());
            
            // Fallback: use expertise matching from database
            $fallbackReviewer = $this->matchByExpertise($title, $reviewerList);
            
            return response()->json([
                'success' => true,
                'suggested_reviewer_id' => $fallbackReviewer['id'],
                'suggested_reviewer_name' => $fallbackReviewer['name'],
                'suggested_reviewer_expertise' => $fallbackReviewer['expertise'],
                'ai_raw_prediction' => 'Error fallback'
            ]);
        }
    }

    /**
     * Match reviewer based on actual expertise from database (fallback method)
     */
    private function matchByExpertise($title, $reviewerList)
    {
        $titleLower = strtolower($title);
        
        // Score each reviewer based on their ACTUAL expertise from database
        $scores = [];
        foreach ($reviewerList as $reviewer) {
            $score = 0;
            $expertiseLower = strtolower($reviewer['expertise']);
            
            // Split expertise into individual keywords/phrases
            $expertiseTerms = preg_split('/[,;]+/', $expertiseLower);
            
            // Extract meaningful words from title (words longer than 3 characters, excluding common words)
            $stopWords = ['the', 'and', 'for', 'with', 'this', 'that', 'from', 'are', 'was', 'were', 'has', 'have', 'been', 'will', 'can', 'could', 'should', 'would', 'about', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'between'];
            $titleWords = explode(' ', $titleLower);
            $titleKeywords = array_filter($titleWords, function($word) use ($stopWords) {
                return strlen($word) > 3 && !in_array($word, $stopWords);
            });
            
            // Match each expertise term against title keywords
            foreach ($expertiseTerms as $term) {
                $term = trim($term);
                if (empty($term)) continue;
                
                // Check if expertise term appears in title
                if (strpos($titleLower, $term) !== false) {
                    $score += 5; // High score for direct phrase match
                }
                
                // Check individual title words against expertise term
                foreach ($titleKeywords as $keyword) {
                    if (strpos($term, $keyword) !== false || strpos($keyword, $term) !== false) {
                        $score += 3;
                    }
                }
            }
            
            // Also check for related terms (if expertise contains broader categories)
            $broaderCategories = [
                'medical' => ['clinical', 'health', 'patient', 'treatment', 'therapy', 'drug', 'medicine', 'hospital', 'disease', 'surgery', 'blood', 'bio', 'anatomy', 'physiology', 'cancer', 'oncology', 'cardio', 'neuro'],
                'legal' => ['law', 'compliance', 'contract', 'regulation', 'policy', 'ethics', 'ethical', 'jurisdiction', 'statute', 'liability', 'privacy', 'confidentiality', 'data protection'],
                'technical' => ['software', 'engineering', 'ai', 'artificial', 'intelligence', 'machine learning', 'data', 'algorithm', 'programming', 'system', 'infrastructure', 'it', 'technology', 'digital', 'computer'],
                'financial' => ['economic', 'market', 'budget', 'cost', 'funding', 'investment', 'revenue', 'profit', 'expense', 'accounting', 'audit', 'finance', 'monetary'],
                'educational' => ['student', 'school', 'university', 'college', 'teaching', 'learning', 'curriculum', 'academic', 'pedagogy', 'education', 'instruction'],
                'social' => ['survey', 'interview', 'focus group', 'behavior', 'psychology', 'sociology', 'community', 'population', 'demographic', 'social science', 'qualitative'],
            ];
            
            // Check for broader category matches
            foreach ($broaderCategories as $category => $relatedTerms) {
                if (strpos($expertiseLower, $category) !== false) {
                    foreach ($relatedTerms as $term) {
                        if (strpos($titleLower, $term) !== false) {
                            $score += 2;
                        }
                    }
                }
            }
            
            $scores[] = ['reviewer' => $reviewer, 'score' => $score];
        }
        
        // Sort by score descending
        usort($scores, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        Log::info('Expertise matching scores based on DB expertise: ' . json_encode(array_map(function($s) {
            return [
                'name' => $s['reviewer']['name'], 
                'score' => $s['score'],
                'expertise' => $s['reviewer']['expertise']
            ];
        }, $scores)));
        
        // Return the highest scoring reviewer
        return $scores[0]['reviewer'] ?? $reviewerList[0];
    }
}