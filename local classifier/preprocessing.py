import os
from keras.preprocessing import sequence
from keras.preprocessing.text import text_to_word_sequence, Tokenizer
import numpy as np

'''
This code preprocesses the data in the 'data' folder 
and produces standard numpy arrays x_train, y_train, x_test, y_test
for training and testing a classifier
'''

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

def get_docs_and_labels(folder):
    '''Produce an array of docs and class labels from a folder
    # Arguments
        folder: folder path
    # Returns
        docs: an array of docs, each element is a string that is the content of a document
        labels: an array of class labels, each element is a class label
    # Example
        input: "./data/train"
        output: 
            ["content of doc1", "cotent of doc2", ...]
            ["alt.atheism", "comp.graphics", ...]
    '''
    docs = []
    labels = []
    for root, dirs, files in os.walk(folder):
        if root != folder:
            for file in files[0:]:
                file_path = os.path.join(root,file)
                text = read(file_path)
                docs.append(text)
                label = root.split(os.path.sep)[-1]
                labels.append(label)
    return docs, labels

def get_word_index(docs):
    '''Produce a word index from a document collection
    # Arguments
        docs: an array of strings
    # Returns
        a word index
    # Example
        input: 
            ["content of doc1", "cotent of doc2", ...]
        output:
            {"content":1, "of":2, ...}
    '''
    # Keras built-in word tokenizer
    t = Tokenizer()
    t.fit_on_texts(docs)
    return t.word_index

def get_label_index(labels):
    '''Produce a label index from an array of text labels
    # Arguments
        labels: an array of text labels
    # Returns:
        a label index
    # Example
        input: 
            ["alt.atheism", "comp.graphics", ...]
        output:
            {"alt.atheism":0, "comp.graphics":1, ...}
    '''
    dic = {}
    c = 0
    for label in labels:
        if label not in dic:
            dic[label] = c
            c += 1
    return dic

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

def encode_docs(docs, word_index):
    '''Encode a document collection into an list of lists, each list is a sequence of integers
    '''
    encoded_docs = []
    for doc in docs:
        encoded_docs.append(encode_doc(doc, word_index))
    return encoded_docs

def encode_labels(labels, label_index):
    '''Encode text class labels into integers
    '''
    encoded_labels = []
    for label in labels:
        encoded_labels.append(label_index[label])
    return encoded_labels

def compute_average_doc_length(docs):
    '''Compute the average lenght of all encoded documents for choosing the proper padding length
    '''
    s = 0
    for doc in docs:
        s += len(doc)
    s /= len(docs)
    return s

def main():

    # set folder paths
    folder_train = os.path.join('data','data','train')
    folder_test = os.path.join('data','data','test')
    # produce document arrays and label arrays 
    docs_train, labels_train = get_docs_and_labels(folder_train)
    docs_test, labels_test = get_docs_and_labels(folder_test)
    # produce word and label indexes
    word_index = get_word_index(docs_train)
    label_index = get_label_index(labels_train)

    save(word_index, os.path.join('data2','word_index.txt'))
    save(label_index, os.path.join('data2','label_index.txt'))

    # calculate vocabulary size for choosing the proper feature size for the text classifier
    print(len(word_index))
    # encode docs and labels into arrays of integers
    docs_train_encoded = encode_docs(docs_train, word_index)
    labels_train_encoded = encode_labels(labels_train, label_index)
    docs_test_encoded = encode_docs(docs_test, word_index)
    labels_test_encoded = encode_labels(labels_test, label_index)

    avg_doc_len_docs_train_encoded = int(compute_average_doc_length(docs_train_encoded))
    # apply zero padding to make each encoded documents equal length
    docs_train_encoded_padded = sequence.pad_sequences(docs_train_encoded, maxlen = avg_doc_len_docs_train_encoded)
    docs_test_encoded_padded = sequence.pad_sequences(docs_test_encoded, maxlen = avg_doc_len_docs_train_encoded)
    
    # produce numpy vectors for training and testing the text classifier
    x_train = np.array(docs_train_encoded_padded, dtype = 'int32')
    x_test = np.array(docs_test_encoded_padded, dtype = 'int32')
    y_train = np.array(labels_train_encoded, dtype = 'int32')
    y_test = np.array(labels_test_encoded, dtype = 'int32')

    for arr in [x_train, y_train, x_test, y_test]:
        print(arr.shape)
    
    np.save(os.path.join('data2','x_train.npy'), x_train)
    np.save(os.path.join('data2','y_train.npy'), y_train)
    np.save(os.path.join('data2','x_test.npy'), x_test)
    np.save(os.path.join('data2','y_test.npy'), y_test)


if __name__ == '__main__':
    main()