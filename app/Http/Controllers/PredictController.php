<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Support\Facades\Log;

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
                'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
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
}