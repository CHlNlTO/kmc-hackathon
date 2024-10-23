import os
from sentence_transformers import SentenceTransformer
import faiss

class SkillRecommender:
    def __init__(self, model_name='all-MiniLM-L6-v2', skills=None, cache_dir=r'C:\Users\Kraum\PROJECTS\kmc-hackathon\flask-server\.cache'):
        os.environ['TRANSFORMERS_CACHE'] = cache_dir
        os.environ['HF_HUB_DISABLE_SYMLINKS_WARNING'] = '1'  

        if skills is None:
            skills = [
                "Python", "JavaScript", "PHP", "Laravel", "AI", "Machine Learning", 
                "Data Analysis", "Natural Language Processing", "Deep Learning", "Cloud Computing"
            ]
        
        self.skills = skills
        self.model = SentenceTransformer(model_name)

        # Encode the predefined skills to create skill embeddings
        self.skill_embeddings = self.model.encode(self.skills, convert_to_tensor=True)
        self.dimension = self.skill_embeddings.shape[1]
        
        # Create and initialize the FAISS index
        self.index = faiss.IndexFlatL2(self.dimension)
        self.index.add(self.skill_embeddings.cpu().detach().numpy())

    def recommend_skills(self, description, top_k=5):
        # Convert description to embedding
        description_embedding = self.model.encode([description], convert_to_tensor=True)

        # Perform vector search in the FAISS index
        distances, indices = self.index.search(description_embedding.cpu().detach().numpy(), top_k)

        # Retrieve the recommended skills using the indices
        recommended_skills = [self.skills[i] for i in indices[0]]

        return recommended_skills


