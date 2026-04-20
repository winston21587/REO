<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PredictController extends Controller
{
    /**
     * Interface with the Python API to predict the IRB review type.
     */
    public function predict(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $apiUrl = env('PREDICT_API_URL', 'https://onnx-reo-ai.onrender.com/predict');

        try {
            Log::info('Attempting AI Prediction for: ' . $request->text);
            Log::info('Target URL: ' . $apiUrl);
            
            // On local setups like Herd/XAMPP, SSL verification often fails without proper CA config.
            // Using withoutVerifying() for local connectivity.

            sleep(rand(1,3)); // just for some cache loading

            $response = Http::timeout(30)->withoutVerifying()->post($apiUrl, [
                'text' => $request->text,
            ]);

            if ($response->successful()) {
                $content = $response->json();
                Log::info('AI Prediction Success Structure: ' . json_encode($content));
                
                // Normalize the response for the frontend
                $label = $content['prediction'] ?? ($content['label'] ?? ($content['predicted_label'] ?? 'Unknown'));
                
                return response()->json([
                    'success' => true,
                    'label' => $label,
                    'prediction' => $content
                ]);
            }

            Log::error('Predict API failed with status ' . $response->status() . ': ' . $response->body());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve prediction from the AI server (Status: ' . $response->status() . ').'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Predict API Connection Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to the AI prediction service: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save the AI prediction to the database.
     */
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
