import os
from keras.preprocessing import sequence
from keras.preprocessing.text import text_to_word_sequence, Tokenizer
import numpy as np
from keras.models import load_model, Model

def read(file):
    '''Reads the content from a file
    # Arguments
        file: file path
    # Returns
        content of the file
    '''

    if not os.path.exists(file):
        return None
    f = open(file, 'r', encoding = 'utf-8', errors = 'ignore')
    content = f.read()
    f.close()
    return content

def save(var, file_path):
    f = open(file_path, "w", encoding='utf-8', errors = 'ignore')
    f.write(str(var))
    f.close()

def load(file_path):
    if not os.path.exists(file_path):
        return None
    c = read(file_path)
    try:
        var = eval(c)
    except:
        return None
    if var:
        return var
    else:
        return None

def encode_doc(doc, word_index):
    '''Encode a document into a sequence of integers
    # Arguments
        doc: a string that represents a document
        word_index: a word index
    # Returns
        a sequence of integers

    # Example
        input: 
            "content of doc1"
        output:
            [1, 2, 3]
    '''
    seq = text_to_word_sequence(doc)
    encoded_seq = []
    for word in seq:
        if word in word_index:
            encoded_seq.append(word_index[word])
    return encoded_seq

def find_label(class_nb, label_index):
    for key in label_index:
        if label_index[key] == class_nb:
            return key
    return None

def main():
    maxlen = 322 # each document is represented as a sequence of 322 integers, each integer represents a word
    
    word_index_file = os.path.join('data2','word_index.txt')
    word_index = load(word_index_file)
    text_file = os.path.join('data','data','test','alt.atheism','53068')
    text = read(text_file)

    text_encoded = encode_doc(text, word_index)
    text_encoded_padded = sequence.pad_sequences([text_encoded], maxlen = maxlen)
    x = np.array(text_encoded_padded, dtype = 'int32')

    best_model_file = os.path.join('data2','bestmodel')
    best_model = load_model(best_model_file)
    y = best_model.predict(x)
    y = np.argmax(y, axis = 1)
    y = y[0]


    label_index_file = os.path.join('data2','label_index.txt')
    label_index = load(label_index_file)
    label = find_label(y, label_index)

    print(label)
    

if __name__ == '__main__':
    main()