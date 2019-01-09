These programs represent my own attempts at implementations of K-means and K-NN algorithms in Python.

Before running any of the programs ensure that NumPy (for calculating mean and standard deviation), SciPy (for calculating Euclidean distance), and Scikit-learn (for comparing results from my K-Means implementation with the results from the scikit-learn K-means implementation) are all installed.

Files that end in '_less' are used with the abridged version of the full dataset (Used for Problem 1 Part 4 and Problem 2 Part 3).

To change the number of clusters in the Kmeans programs (both in the directory 'q1'), change the number in the function call at the bottom (line 177 in kmeans.py; line 173 in kmeans_less.py).

To change the number of neighbors in the Knn programs (both in the directory 'q2'), change the value of the variable k (line 150 in both versions of the program).

To run any program, enter the following in the terminal while in the proper directory:
python <program-name>.

I have included the original assignment prompt that corresponds to these programs. The included report.pdf document contains answers to questions from the assignment.

NOTE: The K-NN implementation is not completely correct. When I first implemented it, I didn't realize that I was suppose to only use values derived from the training dataset to standardize the two datasets. Instead, my program standardizes the two datasets as if they were a whole, resulting in incorrect results. Otherwise, the implementation should be sound.