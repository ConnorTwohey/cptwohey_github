import numpy as np
import tensorflow as tf	#importing the TensorFlow library for use in program
mnist = tf.keras.datasets.mnist	#Importing the Fashion MNIST dataset

(x_train, y_train),(x_test, y_test) = mnist.load_data() #Loading the imported dataset into training and testing instances
x_train, x_test = x_train / 255.0, x_test / 255.0	#Pre-processing training and testing sets

model = tf.keras.models.Sequential([	#Instantiating a model with a sequential/linear stack of layers
  tf.keras.layers.Flatten(),	#Adding a flattening layer (transforms 2D array of pixels to 1D array); Input layer
  tf.keras.layers.Dense(2, activation=tf.nn.relu),	#Adding fully-connected layer with given number of nodes and ReLU as activation/output function. Hidden layer
  tf.keras.layers.Dropout(0.2),	#Layer that simply applies dropout to input. Drops 20% of input units. Hidden layer
  tf.keras.layers.Dense(10, activation=tf.nn.softmax) #fully-connected layer with given number of nodes and softmax as activation/output function. Output layer
])
model.compile(optimizer='adam',	#Compiling model; Using Adam optimizer
              loss='sparse_categorical_crossentropy',	#Selecting loss function
              metrics=['accuracy'])	#Defining accuracy as the metric evaluated by the model

model.fit(x_train, y_train, epochs=5)	#Training the model for five epochs (or iterations on a dataset)
print(model.evaluate(x_test, y_test)) #Returning and printing loss value and metrics value(s) for the model in test mode

wghts = model.get_weights()[0] #Getting list of weights between layer 1 (Flatten) and hidden layer 1 (Dense)

plots = []
for i in xrange(len(wghts[0])):
	flat_plt = []
	for j in xrange(len(wghts)):
		flat_plt.append(wghts[j][i])
	plt = np.reshape(flat_plt, (28, 28))
	plots.append(plt)

#for i in xrange(len(plots)):
	#print 'Plot of Feature',
	#print(i+1),
	#print ':'
	#print(plots[i])

#print(len(plots[0]))
#print(len(plots[0][0]))