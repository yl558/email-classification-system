from __future__ import print_function
import os
import numpy as np
import keras as K
from keras.preprocessing import sequence
from keras.models import Sequential
from keras.layers import Dense, Dropout, Activation
from keras.layers import Embedding
from keras.layers import Conv1D, GlobalMaxPooling1D

from keras.callbacks import ModelCheckpoint
from keras.models import load_model, Model

from sklearn.metrics import f1_score
from matplotlib import pyplot

'''
This code implements an 1D-CNN model for text classification
'''

# set parameters:
max_features = 133171 # vocabulary size
maxlen = 322 # each document is represented as a sequence of 322 integers, each integer represents a word
batch_size = 32 
embedding_dims = 50 # word embedding size
filters = 250 # number of filters in the CNN layer
kernel_size = 3
hidden_dims = 250 
epochs = 10
nb_classes = 20


def diagnostic_plots(history):
    ''' Plots the train/validation loss curve
    '''
    font = {'family' : 'normal', 'size'   : 14}
    pyplot.rc('font', **font)
    pyplot.plot(history.history['loss'])
    pyplot.plot(history.history['val_loss'])
    #pyplot.title('model train vs validation loss')
    pyplot.ylabel('Loss')
    pyplot.xlabel('Epoch')
    pyplot.legend(['Train Loss', 'Validation Loss'], loc='upper right')
    pyplot.show()


def main():

    print('Loading data...')
    
    x_train = np.load(os.path.join('data2','x_train.npy'))
    y_train = np.load(os.path.join('data2','y_train.npy'))
    x_test = np.load(os.path.join('data2','x_test.npy'))
    y_test = np.load(os.path.join('data2','y_test.npy'))

    y_train = K.utils.to_categorical(y_train, nb_classes)
    y_test = K.utils.to_categorical(y_test, nb_classes)

    for arr in [x_train, y_train, x_test, y_test]:
        print(arr.shape)

    print('Build model...')
    # The model is an 1D-CNN model
    model = Sequential()

    # we start off with an efficient embedding layer which maps
    # our vocab indices into embedding_dims dimensions
    model.add(Embedding(max_features,
                        embedding_dims,
                        input_length=maxlen))
    model.add(Dropout(0.2))

    # we add a Convolution1D, which will learn filters
    # word group filters of size filter_length:
    model.add(Conv1D(filters,
                     kernel_size,
                     padding='valid',
                     activation='relu',
                     strides=1))
    # we use max pooling:
    model.add(GlobalMaxPooling1D())

    # We add a vanilla hidden layer:
    model.add(Dense(hidden_dims))
    model.add(Dropout(0.2))
    model.add(Activation('relu'))

    # We project onto a single unit output layer, and squash it with a sigmoid:
    model.add(Dense(nb_classes))
    model.add(Activation('softmax'))

    model.compile(loss='categorical_crossentropy',
                  optimizer='adam',
                  metrics=['accuracy'])
    
    model.summary()

    model_file = os.path.join('data2','bestmodel')
    call_back = ModelCheckpoint(model_file, monitor='val_accuracy', verbose=1, save_best_only=True, save_weights_only=False, mode='auto', period=1)
    
    history = model.fit(x_train, y_train,
              batch_size=batch_size,
              epochs=epochs,
              validation_data=(x_test, y_test),
              callbacks=[call_back],
              verbose = 2)
    
    score, acc = model.evaluate(x_test, y_test, batch_size=batch_size)
    print('Test score:', score)
    print('Test accuracy:', acc)

    diagnostic_plots(history)

    # compute macro f1 score 
    y_pred = model.predict(x_test,batch_size=batch_size)
    y_test = np.argmax(y_test,axis=1)
    y_pred = np.argmax(y_pred,axis=1)
    f1 = f1_score(y_test, y_pred, average='macro')
    print('Test F1 score:', f1)


    
def test():
    print(0)

if __name__ == '__main__':
    #test()
    main()