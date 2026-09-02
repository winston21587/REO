<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test IRB Classification Model</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 font-sans text-slate-800 p-8 max-w-2xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 mb-2">Test Python Classification Model</h2>
        <p class="text-slate-500 text-sm">Upload a sample `.txt`, `.csv`, `.docx`, or `.pdf` file to test the Laravel-to-Python connection.</p>
    </div>

    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm mb-6">
        <form id="testForm" class="space-y-4">
            <!-- CSRF token is necessary for Laravel POST routes -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Select Document</label>
                <input type="file" id="document" name="document" accept=".txt,.csv,.pdf,.docx,.doc" required
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-[#8B0000] hover:file:bg-red-100 transition-colors">
            </div>
            
            <button type="submit" id="submitBtn" 
                class="w-full bg-[#8B0000] text-white font-bold py-3 px-4 rounded-xl hover:bg-red-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors active:scale-95 shadow-md shadow-[#8B0000]/20">
                Classify Document
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm" id="resultBox" style="display: none;">
        <h3 class="text-lg font-bold text-slate-900 mb-2">Result: <span id="finalPrediction" class="text-indigo-600 font-black">...</span></h3>
        <p class="text-sm text-slate-500 mb-4">Chunks Analyzed: <span id="chunksCount" class="font-bold text-slate-700"></span></p>
        
        <h4 class="text-sm font-bold text-slate-700 mb-2">Breakdown:</h4>
        <div class="bg-slate-900 rounded-xl p-4 overflow-x-auto">
            <pre id="jsonResult" class="text-xs text-slate-300 font-mono"></pre>
        </div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submitBtn');
            const resultBox = document.getElementById('resultBox');
            
            btn.innerText = "Processing...";
            btn.disabled = true;
            resultBox.style.display = 'none';

            let formData = new FormData(this);

            try {
                // Hitting the Laravel Controller Route created earlier
                const response = await fetch('/predict-model', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                
                resultBox.style.display = 'block';
                if(data.success) {
                    document.getElementById('finalPrediction').innerText = data.prediction;
                    document.getElementById('chunksCount').innerText = data.chunks_analyzed;
                    document.getElementById('jsonResult').innerText = JSON.stringify(data.breakdown, null, 2);
                } else {
                    document.getElementById('finalPrediction').innerText = "ERROR";
                    document.getElementById('finalPrediction').className = "text-red-600 font-black";
                    document.getElementById('jsonResult').innerText = JSON.stringify(data, null, 2);
                }
            } catch(err) {
                alert("Failed to hit API: " + err);
            } finally {
                btn.innerText = "Classify Document";
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
