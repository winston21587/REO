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
     * Use Groq's free API for IRB classification.
     * This works instantly with no 404 errors.
     */
    public function predict(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $title = $request->text;
        
        $prompt = "Based ONLY on the research title, categorize it into EXACTLY ONE IRB Review Type.\n\n"
            . "DEFINITIONS:\n"
            . "- EXEMPT: Research with NO risk or MINIMAL risk, AND no identifiable data. Examples: anonymous surveys on non-sensitive topics, educational tests, analysis of existing public data, research in normal educational settings, anonymous interviews. NO vulnerable populations. NO identifiable private information.\n\n"
            . "- EXPEDITED: Research with MINIMAL risk BUT involves identifiable data OR specific procedures. Examples: collection of blood samples, non-invasive procedures (MRI, EKG, ultrasound, EEG), moderate exercise, voice recordings, focus groups with sensitive topics, collection of hair/saliva/nails, existing identifiable data, studies with pregnant women (minimal risk only).\n\n"
            . "- FULL BOARD: Research with MORE THAN MINIMAL risk OR involves high-risk VULNERABLE POPULATIONS. Examples: studies with children (unless minimal risk + educational setting), prisoners, cognitively impaired persons, invasive procedures (biopsies, surgery, catheters), experimental drugs/devices, deception studies causing distress, collection of highly sensitive data (HIV status, illegal activities, sexual abuse history).\n\n"
            . "KEY INDICATORS:\n"
            . "- EXEMPT: anonymous, public data, educational tests, normal educational practices, no identifiers\n"
            . "- EXPEDITED: blood draw, MRI, EEG, EKG, ultrasound, saliva, hair, nails, focus group, identifiable survey, voice recording, moderate exercise, existing identifiable data, pregnant women\n"
            . "- FULL BOARD: children, prisoners, cognitively impaired, invasive procedure, biopsy, surgery, experimental drug, deception, trauma, abuse, HIV, illegal behavior, more than minimal risk\n\n"
            . "IMPORTANT: Default to EXEMPT for truly anonymous minimal risk studies. Use EXPEDITED for minimal risk studies with identifiable data or specific allowed procedures. Use FULL BOARD for anything exceeding minimal risk.\n\n"
            . "Research Title: \"$title\"\n\n"
            . "Respond with ONLY the category name (EXEMPT, EXPEDITED, or FULL BOARD) followed by a colon and a one-sentence reason.\n"
            . "Category:";

        try {
            Log::info('Attempting Groq AI Prediction for: ' . $title);

            $response = Groq::chat()->completions()->create([
                'model' => env('GROQ_MODEL', 'GROQ_MODEL'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an IRB classification expert. Analyze research titles and categorize them as EXEMPT, EXPEDITED, or FULL BOARD review types based on federal guidelines. Always respond with EXACTLY the category name followed by a colon and a brief reason.'
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
            Log::info('Groq Response: ' . $rawOutput);
            
            // Extract the classification
            $label = $this->extractLabelFromOutput($rawOutput);
            
            return response()->json([
                'success' => true,
                'label' => $label,
                'raw_prediction' => $rawOutput,
            ]);

        } catch (\Exception $e) {
            Log::error('Groq API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'AI Service Error: ' . $e->getMessage()
            ], 500);
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
        $request->validate([
            'title' => 'required|string',
        ]);

        $apiKey = env('GROQ_API_KEY');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false, 
                'message' => 'API key not configured'
            ], 500);
        }

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
        
        $systemPrompt = "You are an expert research coordinator. Your task is to assign the most appropriate reviewer for a research project.\n\n"
            . "Available reviewers and their ACTUAL EXPERTISE (from their profiles):\n{$reviewerOptions}\n"
            . "INSTRUCTIONS:\n"
            . "1. Read the research title carefully\n"
            . "2. Compare the research topic with each reviewer's EXPERTISE (listed above)\n"
            . "3. Choose the reviewer whose expertise MOST CLOSELY matches the research topic\n"
            . "4. Consider keywords in the title and match them to the expertise areas\n"
            . "5. Respond with ONLY the reviewer's FULL NAME exactly as written above\n"
            . "6. Do not add any extra text, punctuation, or explanation\n\n"
            . "Valid reviewer names: " . implode(', ', array_column($reviewerList, 'name'));
        
        $userPrompt = "Research Title: \"$title\"\n\n"
            . "Based on matching the research topic with the reviewer's ACTUAL EXPERTISE, which reviewer should be assigned?\n\n"
            . "Reviewer Name:";

        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
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

            if ($response->successful()) {
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