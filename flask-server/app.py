from flask import Flask, render_template, request
import spacy

app = Flask(__name__)


nlp = spacy.load("en_core_web_sm")

def extract_project_data(text):

    doc = nlp(text)
    data = {}

    for ent in doc.ents:
        if ent.label_ == "ORG": 
            data['project_name'] = ent.text
        elif ent.label_ == "MONEY":  
            data['budget'] = ent.text
        elif ent.label_ == "DATE":  
            data['timeline'] = ent.text


    skills = ['Python', 'JavaScript', 'PHP', 'Laravel', 'AI', 'Machine Learning']
    data['skills'] = [skill for skill in skills if skill.lower() in text.lower()]

    return data

@app.route('/')
def form():
    return render_template('form.html', data={})

@app.route('/autofill', methods=['POST'])
def autofill():

    project_description = request.form['description']

    extracted_data = extract_project_data(project_description)

    return render_template('form.html', data=extracted_data)


if __name__ == '__main__':
    app.run(debug=True)
