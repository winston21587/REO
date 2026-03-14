import pandas as pd
import random
from sklearn.model_selection import train_test_split

# 1. Define typical patterns for each category
data_templates = {
    "Exempt": [
        "Anonymous survey regarding workplace satisfaction among corporate employees.",
        "Observation of public behavior in a city park without any identifiers recorded.",
        "Retrospective review of de-identified medical records for frequency of flu symptoms.",
        "Analysis of existing public datasets regarding census information.",
        "Evaluation of a new math curriculum in a standard 4th-grade classroom environment.",
        "Secondary analysis of anonymized blood pressure data from a 2010 study."
    ],
    "Expedited": [
        "Collection of saliva samples from healthy adults to measure cortisol levels.",
        "Interviews with teachers about stress levels where names are linked to responses.",
        "Study using non-invasive heart rate monitors during light treadmill exercise.",
        "Focus groups regarding sensitive personal experiences, but with minimal risk of harm.",
        "Small blood draw via venipuncture from healthy non-pregnant adults.",
        "Research on individual or group behavior using recorded voice or video data."
    ],
    "Full Board": [
        "Clinical trial testing a new pharmaceutical drug for hypertension in elderly patients.",
        "Research involving interviews with incarcerated individuals about prison conditions.",
        "Study on the psychological impact of past trauma in children under 10.",
        "Evaluating a high-risk surgical procedure for spinal cord injury reconstruction.",
        "Surveying active drug users about illegal activities with identifiable data collection.",
        "Research involving intentional deception of participants regarding physical safety."
    ]
}

# 2. Generate 1,500 entries (500 per category for a balanced dataset)
data = []
for label, templates in data_templates.items():
    for _ in range(500):
        # We add minor variations to simulate different research abstracts
        base_text = random.choice(templates)
        variations = [
            " This study aims to...", 
            " Participants will be asked to...", 
            " Data will be collected via...", 
            " The primary objective is..."
        ]
        final_text = f"{base_text}{random.choice(variations)} {random.randint(10, 500)} subjects involved."
        data.append({"text": final_text, "label": label})

# 3. Create DataFrame and Shuffle
df = pd.DataFrame(data)
df = df.sample(frac=1).reset_index(drop=True)

# 4. Split the data (80% training, 20% testing)
train_df, test_df = train_test_split(df, test_size=0.20, random_state=42, stratify=df['label'])

# 5. Save to CSV
train_df.to_csv('training.csv', index=False)
test_df.to_csv('test.csv', index=False)

print(f"Successfully generated 1,500 samples.")
print(f"training.csv: {len(train_df)} rows")
print(f"test.csv: {len(test_df)} rows")