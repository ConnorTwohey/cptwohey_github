import csv
import numpy as np
import sys
import copy
import operator
from scipy.spatial import distance

with open('NBAstats_less.csv', 'rb') as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=',')
	columns = 9
	rows = sum(1 for row in csv_file) - 1
	csv_file.seek(0)
	line_count = 0
	playerList = []
	for row1 in xrange(rows): 
		playerList += [[0]*columns]

	for row in csv_reader:
		if line_count == 0:
			line_count += 1
		else:
			playerList[line_count-1] = row
			line_count += 1

	#print(playerList[0])

	#Separating names from numerical attributes
	#This is needed so that data points can properly be
	#compared with centroids later
	names = []
	numbers = []
	for i in xrange(rows):
		numbers += [[0]*(columns-1)]
	i = 0
	while i < rows:
		names.append(playerList[i][0])
		i += 1
	i = 0
	while i < rows:
		j = 1
		while j < columns:
			if j == 1:
				#Changing Positions to numbers (for clustering)
				if playerList[i][j] == 'C':
					numbers[i][j-1] = 1
				elif playerList[i][j] == 'PF':
					numbers[i][j-1] = 2
				elif playerList[i][j] == 'SF':
					numbers[i][j-1] = 3
				elif playerList[i][j] == 'SG':
					numbers[i][j-1] = 4
				else:
					numbers[i][j-1] = 5
			else:
				numbers[i][j-1] = float(playerList[i][j])
			j += 1
		i += 1
	#numbers = scale(numbers)

	#used later with the external K module
	numbersCopy = copy.deepcopy(numbers)

	#Calculating means and standard deviations for each attribute
	#Will be used to standardize data
	means = []
	stdev = []

	i = 0
	while i < columns-1:
		#used for calculating stdev
		columnVals = []
		#used for calculating mean
		sum = 0
		j = 0
		while j < rows:
			sum += float(numbers[j][i])
			columnVals.append(float(numbers[j][i]))
			j += 1
		means.append(sum / rows)
		stdev.append(np.std(columnVals))
		i += 1

	#Standardizing the data
	i = 0
	while i < columns-1:
		j = 0
		while j < rows:
			numbers[j][i] = (float(numbers[j][i]) - means[i])/float(stdev[i])
			j += 1
		i += 1

	#Splitting data into training and testing sets
	training = []
	testing = []
	i = 0
	while i < 375:
		training.append((names[i], numbers[i]))
		i += 1
	i = 0
	while i < 100:
		testing.append((names[i+375], numbers[i+375]))
		i += 1

	#print(len(training))
	#print(len(testing))
	#print(testing[len(testing)-1])
	#print(numbers[len(numbers)-1])

	np.random.seed(1)

	#Getting k neighbors nearest to the data point test
	def getNeighbors(trainSet, test, k):
		#Holds (Data Instance, Distance) pairs
		distances = []
		for i in range(len(trainSet)):
			#Getting all distances, will sort later
			dst = distance.euclidean(test[1], trainSet[i][1])
			distances.append((trainSet[i], dst))
		#Sorting the pairs by distance (small to large)
		distances.sort(key=operator.itemgetter(1))
		neighbor = []
		#Getting k nearest neighbors (numbers and names)
		for j in range(k):
			neighbor.append(distances[j][0])
		return neighbor

	#I will assume that the position is meant to be the class
	def getPrediction(neighbors):
		classVote = {}
		for i in range(len(neighbors)):
			#Pulling position value from numbers vector for current neighbor
			predict = neighbors[i][1][0]
			if predict in classVote:
				classVote[predict] += 1
			else:
				classVote[predict] = 1
		#Ordering from most to least votes
		sortVotes = sorted(classVote.iteritems(), key=operator.itemgetter(1), reverse=True)
		#print sortVotes[0][0]
		return sortVotes[0][0]

	def getAccuracy(testSet, predict):
		accurate = 0
		for i in range(len(testSet)):
			if testSet[i][1][0] == predict[i]:
				accurate += 1
		return (accurate / float(len(testSet))) * 100.0

	predictions = []
	k = 30
	#All predicted/actual values are in standardized form
	for x in range(len(testing)):
		neighbors = getNeighbors(training, testing[x], k)
		result = getPrediction(neighbors)
		predictions.append(result)
		print 'predicted =' + repr(result) + ', actual = ' + repr(testing[x][1][0])
	#print(predictions)
	accuracy = getAccuracy(testing, predictions)
	print 'Accuracy: ' + repr(accuracy) + '%'
