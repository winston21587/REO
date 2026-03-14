import os
import pickle
import numpy as np
import pandas as pd
from collections import Counter
from flask import Flask, request, jsonify
from werkzeug.utils import secure_filename
import traceback

from docx import Document
import PyPDF2

app = Flask(__name__)

# Config
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_FILE = os.path.join(BASE_DIR, 'irb_model.pkl')
UPLOAD_FOLDER = os.path.join(BASE_DIR, 'uploads')
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
CHUNK_SIZE_WORDS = 200

# Load Model
try:
    with open(MODEL_FILE, 'rb') as f:
        model = pickle.load(f)
    print(f"--- Model Loaded Successfully from {MODEL_FILE} ---")
except FileNotFoundError:
    model = None
    print(f"Error: '{MODEL_FILE}' not found.")

def get_text_chunks(text, chunk_size):
    """Splits text into chunks of a specific word count."""
    words = str(text).split()
    for i in range(0, len(words), chunk_size):
        yield " ".join(words[i:i + chunk_size])

def extract_text(file_path):
    """Handles multiple file formats and returns raw text."""
    if not os.path.exists(file_path):
        return None
    ext = os.path.splitext(file_path)[1].lower()
    try:
        if ext == '.txt':
            with open(file_path, 'r', encoding='utf-8') as f:
                return f.read()
        elif ext == '.csv':
            df = pd.read_csv(file_path)
            return " ".join(df.astype(str).values.flatten())
        elif ext == '.docx':
            doc = Document(file_path)
            return " ".join([para.text for para in doc.paragraphs])
        elif ext == '.pdf':
            text = ""
            with open(file_path, 'rb') as f:
                reader = PyPDF2.PdfReader(f)
                for page in reader.pages:
                    text_content = page.extract_text()
                    if text_content:
                        text += text_content + " "
            return text
        else:
            return None
    except Exception as e:
        print(f"Extraction error: {e}")
        return None

@app.route("/predict", methods=["POST"])
def predict():
    if model is None:
        return jsonify({"error": "Model not loaded on server."}), 500

    if 'file' not in request.files:
        return jsonify({"error": "No file part in the request."}), 400
        
    file = request.files['file']
    if file.filename == '':
        return jsonify({"error": "No selected file."}), 400
        
    if file:
        filename = secure_filename(file.filename)
        file_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        file.save(file_path)
        
        try:
            # 1. Extract text from uploaded file
            text_data = extract_text(file_path)
            if not text_data or len(text_data.strip()) == 0:
                os.remove(file_path)
                return jsonify({"error": "No text could be extracted from the file."}), 400
                
            # 2. Chunk text
            chunks = list(get_text_chunks(text_data, CHUNK_SIZE_WORDS))
            if not chunks:
                os.remove(file_path)
                return jsonify({"error": "Not enough text to analyze."}), 400
                
            # 3. Predict & vote
            preds = model.predict(chunks)
            vote_count = Counter(preds)
            final_determination = vote_count.most_common(1)[0][0]
            
            # Clean up uploaded file
            if os.path.exists(file_path):
                os.remove(file_path)
                
            # Breakdown data
            breakdown = {}
            for label, count in vote_count.items():
                percentage = (count / len(preds)) * 100
                breakdown[label] = {
                    "count": count,
                    "percentage": round(percentage, 1)
                }
                
            return jsonify({
                "success": True,
                "prediction": final_determination.upper(),
                "chunks_analyzed": len(chunks),
                "breakdown": breakdown
            })
            
        except Exception as e:
            # Clean up on error
            if os.path.exists(file_path):
                os.remove(file_path)
            return jsonify({"error": str(e), "traceback": traceback.format_exc()}), 500

@app.route("/health", methods=["GET"])
def health_check():
    return jsonify({"status": "healthy", "model_loaded": model is not None})

if __name__ == "__main__":
    app.run(host="127.0.0.1", port=5001, debug=True)
