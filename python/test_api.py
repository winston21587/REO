import requests

url = 'http://127.0.0.1:5001/predict'
files = {'file': open('training.csv', 'rb')}

response = requests.post(url, files=files)
print("Status Code:", response.status_code)
print("Response:", response.json())
