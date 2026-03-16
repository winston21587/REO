<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PredictionController extends Controller
{
    /**
     * Send file to the Python prediction API.
     */
    public function predict(Request $request)
    {
        set_time_limit(120); // Increase max execution time for long AI processing

        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('document');
            // Rely on PYTHON_API_URL in .env, default to localhost:5001
            $apiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5001') . '/predict';
            
            // Attach the uploaded file to the HTTP request
            $response = Http::timeout(30)->attach(
                'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
            )->post($apiUrl);

            // Handle the response from Python
            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'prediction' => $data['prediction'] ?? 'UNKNOWN',
                    'breakdown' => $data['breakdown'] ?? [],
                    'chunks_analyzed' => $data['chunks_analyzed'] ?? 0,
                ]);
            } else {
                $errorData = $response->json();
                Log::error('Python API Error: ', $errorData ?? []);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error from prediction service.',
                    'details' => $errorData['error'] ?? 'Unknown error'
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Prediction integration failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to the prediction service. Ensure the Python server is running.',
            ], 500);
        }
    }
}
