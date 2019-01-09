import numpy as np
import sys
import math

#activation function = sigmoid; used to map all values to between 0 and 1
#Objective function = loss function = cross entropy/log-loss
#link/prediction function:

#Defining variables of distribution
mu0 = [1, 0]
mu1 = [0, 1.5]
sigma0 = np.zeros((2, 2))
sigma1 = np.zeros((2, 2))
sigma0[0][0] = 1
sigma0[0][1] = 0.75
sigma0[1][0] = 0.75
sigma0[1][1] = 1
sigma1[0][0] = 1
sigma1[0][1] = 0.75
sigma1[1][0] = 0.75
sigma1[1][1] = 1
weights = [1, 1, 1]
l_rate = 1
train_iterations = 10000

#Creating testing and training sets
train0 = np.random.multivariate_normal(mu0, sigma0, 1000)
train1 = np.random.multivariate_normal(mu1, sigma1, 1000)
test0 = np.random.multivariate_normal(mu0, sigma0, 500)
test1 = np.random.multivariate_normal(mu1, sigma1, 500)

#Also acts as activation function
def sigmoid(z):
	return 1/(1+np.exp(-z))

#The logit function is used as link function
#In actuality, the linear link function and the logit function
#give equivalent values, so either works
def link(variables, w, size):
	#Zs = []
	logit = []
	for i in xrange(size):
		z = w[0] + w[1]*variables[i][0] + w[2]*variables[i][1]
		sigZ = sigmoid(z)
		#Zs.append(z)
		logit.append(np.log(sigZ / (1 - sigZ)))
	#print(Zs)
	#print '\n\n\n'
	return logit

#Cross Entropy is used for the objective function
#Since a set belongs to one class as a whole, the objective function
#needs to know which class that set belongs to so that Yi has the right value
#def objective(logits, size, classNo):
#	y = 0
#	if classNo == 1:
#		y = 1
#	crossSum = 0
#	for i in xrange(size):
#		crossSum += (y * np.log(sigmoid(logits[i]))) + (1-y)*(np.log(1-sigmoid(logits[i])))
#	crossSum = -crossSum
#	return crossSum

def predictions(logits, size):
	p = []
	for i in xrange(size):
		p.append(sigmoid(logits[i]))
	return p

def gradient_descent(error, train, size):
	np.transpose(train)
	#print(len(train))
	#print(len(error))
	#print(train)
	#print(error)
	gradient = np.dot(train, error) / size
	return gradient

#Estimating LogReg coefficients for training set using gradient descent
#n is the number of iterations (defined at top)
#This also essentially acts as the learning algorithm
def coefficients(train, weights, lr, n, classNo, size):
	coef = weights
	t = link(train, weights, size)
	p = predictions(t, size)
	for i in xrange(n):
		error = []
		for j in xrange(size):
			yhat = p[j]
			error.append(classNo - yhat)
		grad = gradient_descent(error, p, size)
		#print(grad)
		#print(i)

		coef += -lr * grad
	return coef


print 'testing link/prediction function'
t0 = link(train1, weights, 1000)
#print(t0)

coef0 = coefficients(train0, weights, l_rate, train_iterations, 0, 1000)
print(coef0)

