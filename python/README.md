# WMSU REOC AI 

[![Python 3.8+](https://img.shields.io/badge/python-3.8+-blue.svg)](https://www.python.org/downloads/)
[![Scikit-Learn](https://img.shields.io/badge/Library-Scikit--Learn-orange.svg)](https://scikit-learn.org/)

The AI engine for the **WMSU REOC Portal**. This component uses Natural Language Processing (NLP) to automatically categorize research proposals into one of three regulatory tracks based on their risk level: **Full**, **Expedited**, or **Exempt**.

---

## 🧠 How it Works

The classifier utilizes a **Multi-Layer Perceptron (MLP)** neural network architecture paired with **TF-IDF Vectorization** to analyze the text of research abstracts or titles.



### Classification Categories:
1.  **Exempt:** Research involving minimal risk (e.g., public data, anonymous surveys).
2.  **Expedited:** Research involving no more than minimal risk but requires specific oversight.
3.  **Full Review:** Research involving more than minimal risk or vulnerable populations.

---

## 🛠 Tech Stack
* **Python:** The core programming language.
* **Pandas:** For data manipulation and dataset loading.
* **Scikit-Learn:** * `TfidfVectorizer`: To convert text into numerical features.
    * `MLPClassifier`: A neural network model for the classification task.
    * `Pipeline`: To bundle the vectorizer and classifier into a single object.
* **Pickle:** For serializing and saving the trained model for use in the Flutter app/backend.

