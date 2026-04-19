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
            $response = Http::timeout(30)->post($apiUrl, [
                'text' => $request->text,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'prediction' => $response->json()
                ]);
            }

            Log::error('Predict API failed: ' . $response->body());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve prediction from the AI server.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Predict API connection error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to the AI prediction service.'
            ], 500);
        }
    }
}
