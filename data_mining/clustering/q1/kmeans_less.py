import csv
import numpy as np
import sys
import copy
from scipy.spatial import distance
#KMeans module only used for verification of my own implementation (See very last code block at very bottom)
from sklearn.cluster import KMeans as km

#26 numeric attributes
#1 non-numeric attribute (names)

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

	#Used at the end of program
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

	np.random.seed(1)
	#The data is now ready for clustering
	def mykmeans(data, clusters):
		clusterSet = []
		oldClusterSet = []
		#This container will keep track of the names of the players
		#belonging to specific clusters
		
		for i in xrange(clusters):
			#Clusters themselves don't care about names, so we only need the 26 numeric attributes
			#Hence (columns-1)
			clusterSet += [[0]*(columns-1)]
			oldClusterSet += [[0]*(columns-1)]
		#placing initial clusters
		i=0
		while i < len(clusterSet):
			j=0
			while j < columns-1:
				clusterSet[i][j] = np.random.uniform()
				j += 1
			i += 1

		#With the raw numbers in their own container, can now easily find 
		#Euclidean distances between data points and centroids

		changed = True

		while changed == True:
			clusterNames = [[] for x in xrange(clusters)]
			oldClusterSet = copy.deepcopy(clusterSet)
			#Assigning points to clusters
			for i in xrange(len(numbers)):
				#Arbitrary number far larger than largest possible data value
				minDst = 100
				#To keep track of which number cluster it is closest to.
				minCluster = 0
				for j in xrange(clusters):
					dst = distance.euclidean(numbers[i], clusterSet[j])
					if dst < minDst:
						minDst = dst
						minCluster = j
					j += 1
				clusterNames[minCluster].append(names[i])
				i += 1

			#Next, need to recompute position of centroids
			#To do this, need means of assigned points by cluster
			#First, need to get the numbers of all points that are
			#grouped in the same cluster
			newAvgs = []
			for x in xrange(clusters):
				newAvgs += [[0]*(columns-1)]


			#Making sure that the right numbers are grouped with the right cluster
			for i in xrange(len(clusterNames)):
				#Container that will hold all data points of current cluster
				numberCluster = []
				for j in xrange(len(clusterNames[i])):
					for k in xrange(len(playerList)):
						if clusterNames[i][j] == playerList[k][0]:
							numberCluster.append(numbers[k])
							
				for j in xrange(len(clusterSet)):
					for k in xrange(columns-1):
						num = 0
						for g in xrange(len(numberCluster)):
							num += numberCluster[g][k]
						newAvgs[i][k] = num/rows

			#Moving centroids to new mean positions
			clusterSet = copy.deepcopy(newAvgs)

			#Checking if centroids have moved
			changed = False
			for i in xrange(len(clusterSet)):
				if clusterSet[i] != oldClusterSet[i]:
					changed = True
		return clusterNames, clusterSet


	names, clusters = mykmeans(numbers, 5)
	print(len(names[0]))
	print(len(names[1]))
	print(len(names[2]))

	#"Unstandardizing" the clusters
	i=0
	while i < len(clusters):
		j=0
		while j < len(clusters[i]):
			clusters[i][j] = round((clusters[i][j] * stdev[j]) + means[j], 5)
			j += 1
		i += 1

	print 'Clusters (Unstandardized):'
	print clusters

		#Reporting position distributions
	i=0
	while i < len(names):
		j = 0
		cCount = 0
		pfCount = 0
		sfCount = 0
		sgCount = 0
		pgCount = 0
		while j < len(names[i]):
			k = 0
			while k < len(playerList):
				if names[i][j] == playerList[k][0]:
					if playerList[k][1] == 'C':
						cCount += 1
					elif playerList[k][1] == 'PF':
						pfCount += 1
					elif playerList[k][1] == 'SF':
						sfCount += 1
					elif playerList[k][1] == 'SG':
						sgCount += 1
					else:
						pgCount += 1
				k += 1
			j += 1
		print 'Number of centers in cluster ',
		print(i+1),
		print ': ',
		print(cCount)
		print 'Number of power forwards in cluster ',
		print(i+1),
		print ': ',
		print(pfCount)
		print 'Number of small forwards in cluster ',
		print(i+1),
		print ': ',
		print(sfCount)
		print 'Number of shooting guards in cluster ',
		print(i+1),
		print ': ',
		print(sgCount)
		print 'Number of point guards in cluster ',
		print(i+1),
		print ': ',
		print(pgCount)
		i += 1

	#uncomment this to see results from sklearn KMeans module
	#k = km(n_clusters=3, init='random')
	#k = k.fit(numbersCopy)
	#labels = k.predict(numbersCopy)
	#centroids = k.cluster_centers_
	#print(centroids)
	#print(labels)

