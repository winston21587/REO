import pandas as pd
import pickle
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neural_network import MLPClassifier
from sklearn.pipeline import Pipeline

print("Loading training data...")
train_df = pd.read_csv('training.csv')
X_train = train_df['text']
y_train = train_df['label']

print("Building pipeline...")
pipeline = Pipeline([
    ('tfidf', TfidfVectorizer(max_features=5000, stop_words='english')),
    ('clf', MLPClassifier(hidden_layer_sizes=(100, 50), max_iter=500, random_state=42, verbose=True))
])

print("Training model...")
pipeline.fit(X_train, y_train)

print("Saving model to irb_model.pkl...")
with open('irb_model.pkl', 'wb') as f:
    pickle.dump(pipeline, f)

print("Done! The model is now fully compatible with this environment.")
