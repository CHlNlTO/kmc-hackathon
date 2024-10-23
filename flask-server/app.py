from flask import Flask, render_template, request
import spacy
from skill_recommender import SkillRecommender

app = Flask(__name__)
recommender = SkillRecommender()
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
    
    data['skills'] = recommender.recommend_skills(text)

    return data

@app.route('/')
def form():
    return render_template('form.html', data={})

@app.route('/autofill', methods=['POST'])
def autofill():
    description = request.form['description']
    extracted_data = extract_project_data(description)
    return render_template('form.html', data = extracted_data)

@app.route('/submit', methods=['POST'])
def submit():
    project_name = request.form['project_name']
    budget = request.form['budget']
    timeline = request.form['timeline']
    skills = request.form['skills']

    print (f"Submitted Project: {project_name}, Budget: {budget}, Timeline: {timeline}, Skills: {skills}")

    return f"Submitted Project: {project_name}, Budget: {budget}, Timeline: {timeline}, Skills: {skills}"


if __name__ == '__main__':
    app.run(debug=True)


    