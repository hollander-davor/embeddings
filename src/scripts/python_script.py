import sys
import json
import numpy as np 
import faiss

#------------------------euklidsko rastojanje
#load file with vectors an put it in data variable
file_path = sys.argv[1]
with open(file_path,'r') as file:
    data = json.load(file)
    
#convert vectors array to numpy array
np_vectors = np.array(data['vectors'],dtype=np.float32)
search_vector = np.array(data['searchVector'],dtype=np.float32).reshape(1, -1)

#vector dimesnion
dimension = np_vectors.shape[1]

#initialize faiss flat index 
index = faiss.IndexFlatL2(dimension)#Euclid distance L2
#add vectors to index
index.add(np_vectors)


#perform search
query = np.array(search_vector, dtype=np.float32)  # Example query vector
k = 3  # Number of nearest neighbors to search for
distances, indices = index.search(query, k)

result = {
    'indices': indices.tolist()#numpy array to python list
}

print(json.dumps(result))
#------------euklidsko rastojanje kraj------------------------

#---------kosinusna slicnost----------------------------
# file_path = sys.argv[1]
# with open(file_path,'r') as file:
#     data = json.load(file)
# #numpy array
# vectors_np = np.array(data['vectors'], dtype=np.float32)

# #Normalize the vectors to unit length (to use cosine similarity via L2 distance)
# faiss.normalize_L2(vectors_np)

# # Create a FAISS index (IndexFlatL2 for exact search)
# index = faiss.IndexFlatL2(vectors_np.shape[1])  # 'd' is the dimensionality of the vectors
# index.add(vectors_np)  # Add the normalized vectors to the index

# search_vector = np.array(data['searchVector'], dtype=np.float32).reshape(1, -1)

# # Normalize the query vector to unit length as well
# faiss.normalize_L2(search_vector)

# k = 3  # Number of nearest neighbors to retrieve
# distances, indices = index.search(search_vector, k)

# result = {
#     'indices': indices.tolist()#numpy array to python list
# }

# print(json.dumps(result))
#---------kosinusna slicnost----------kraj------------------


#----------kosinusna slicnost--2--------------------------
# file_path = sys.argv[1]
# with open(file_path,'r') as file:
#     data = json.load(file)
# #numpy array
# vectors_np = np.array(data['vectors'], dtype=np.float32)
# search_vector = np.array(data['searchVector'], dtype=np.float32).reshape(1,-1)

# #normalize vectors
# vectors_np = vectors_np / np.linalg.norm(vectors_np, axis=1, keepdims=True)
# search_vector = search_vector / np.linalg.norm(search_vector, axis=1, keepdims=True)

# #vector dimension
# dimension = vectors_np.shape[1]


# #create index
# index = faiss.IndexFlatIP(dimension)

# index.add(vectors_np)

# k=3
# distances,indices = index.search(search_vector,k)

# result = {
#     'indices': indices.tolist()#numpy array to python list
# }

# print(json.dumps(result))


#--------kosinusna slicnost--2--kraj-----------------------


