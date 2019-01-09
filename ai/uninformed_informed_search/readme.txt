Connor Twohey
UTA ID: 1001177282
Language Used: C++

Since we were advised to use Uniform Cost Search to create this program, I created a struct to represent paths/routes between cities, as well as a class that could be used in a priority queue later on, when I implemented the function that uses Uniform Cost Search to find the optimal path. My findPath function takes the name of the starting city and the destination, as well as a copy of the map that was created by reading the input file. The map is represented by a 2D array with three columns. This function uses a priority queue that holds pathInfo structs (and prioritizes paths with lower distances), and implements uniform-cost search using that priority queue with the map generated from the input file.

To compile this program, open the terminal/command prompt and navigate to the folder containing the file “task1.cpp”. Next, enter the following command: g++ task1.cpp -o find_route
To run the program, place the input txt file in the same folder as the task1.cpp file.
Finally, to run the program, enter the following command (while inserting the appropriate names):
./find_route <file-name> <starting-city> <destination>

IMPORTANT: If the txt file being used contains more than 50 entries, open the task1.cpp file and raise the int named mapSize, on line 14. Do this before compiling and running.

Note (1-3-19): I have included the original assignment prompt. The required specifications for the program are listed there, as are the questions for the written portion.