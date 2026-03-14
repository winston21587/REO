<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test IRB Classification Model</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        .box { border: 1px solid #ccc; padding: 20px; border-radius: 8px; margin-top: 20px;}
        button { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:disabled { background: #aaa; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>

    <h2>Test Python Classification Model</h2>
    <p>Upload a sample `.txt`, `.csv`, `.docx`, or `.pdf` file to test the Laravel-to-Python connection.</p>

    <div class="box">
        <form id="testForm">
            <!-- CSRF token is necessary for Laravel POST routes -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <input type="file" id="document" name="document" accept=".txt,.csv,.pdf,.docx,.doc" required>
            <br><br>
            <button type="submit" id="submitBtn">Classify Document</button>
        </form>
    </div>

    <div class="box" id="resultBox" style="display: none;">
        <h3>Result: <span id="finalPrediction" style="color: blue;">...</span></h3>
        <p>Chunks Analyzed: <span id="chunksCount"></span></p>
        
        <h4>Breakdown:</h4>
        <pre id="jsonResult"></pre>
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
