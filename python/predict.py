import pandas as pd
import pickle
import numpy as np
from collections import Counter
import os

# Specialized libraries for file extraction
from docx import Document
import PyPDF2

# 1. ROBUST PATH CONFIGURATION
# This ensures the script looks in its own folder for the model
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_FILE = os.path.join(BASE_DIR, 'irb_model.pkl')

# List your files here
FILES_TO_PROCESS = ['sample.pdf', 'sample.docx', 'sample-research-proposal.pdf'] 
CHUNK_SIZE_WORDS = 200 

# 2. LOAD THE MODEL
try:
    with open(MODEL_FILE, 'rb') as f:
        model = pickle.load(f)
    print(f"--- Model Loaded Successfully from {MODEL_FILE} ---")
except FileNotFoundError:
    print(f"Error: '{MODEL_FILE}' not found.")
    print(f"Search directory: {BASE_DIR}")
    print("Please ensure your .pkl file is in the same folder as this script.")
    exit()

# 3. EXTRACTION AND CHUNKING FUNCTIONS
def get_text_chunks(text, chunk_size):
    """Splits text into chunks of a specific word count."""
    words = str(text).split()
    for i in range(0, len(words), chunk_size):
        yield " ".join(words[i:i + chunk_size])

def extract_text(file_path):
    """Handles multiple file formats and returns raw text."""
    if not os.path.exists(file_path):
        print(f"File not found: {file_path}")
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
            print(f"Unsupported extension: {ext}")
            return None
    except Exception as e:
        print(f"Error extracting {ext} file: {e}")
        return None

# 4. PROCESSING LOGIC
all_chunk_predictions = []

print(f"Starting processing for {len(FILES_TO_PROCESS)} files...\n")

for file_path in FILES_TO_PROCESS:
    # Resolve relative paths to absolute paths
    full_path = os.path.join(BASE_DIR, file_path)
    
    text_data = extract_text(full_path)
    
    if text_data and len(text_data.strip()) > 0:
        chunks = list(get_text_chunks(text_data, CHUNK_SIZE_WORDS))
        if chunks:
            # Predict labels for each chunk
            preds = model.predict(chunks)
            all_chunk_predictions.extend(preds)
            print(f"Processed: {file_path} ({len(chunks)} chunks analyzed)")
    else:
        print(f"Warning: No text could be extracted from {file_path}")

# 5. FINAL AGGREGATED OUTPUT
if all_chunk_predictions:
    vote_count = Counter(all_chunk_predictions)
    # The 'most_common(1)' returns [(label, count)]
    final_determination = vote_count.most_common(1)[0][0]
    
    print("\n" + "="*45)
    print(f"FINAL CONSOLIDATED IRB TYPE: {final_determination.upper()}")
    print("-" * 45)
    print("Breakdown of detections across all documents:")
    for label, count in vote_count.items():
        percentage = (count / len(all_chunk_predictions)) * 100
        print(f"- {label}: {count} chunks ({percentage:.1f}%)")
    print("="*45)
else:
    print("\nResult: Prediction failed. No text data was collected from the provided files.")