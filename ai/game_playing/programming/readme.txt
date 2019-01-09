Name: Connor Twohey
UTA ID: 1001177282
Language: C++

NOTE (Jan. 2, 2019): This is my attempt at creating a program that simulates an AI that optimally makes a move/plays a game of Connect 4. Unfortunately, at the time, I was unable to correctly implement the AI's behavior.

The structure of the code is largely unchanged from the structure of the original skeleton code file. I just changed the order that some functions appeared so I could use them in other functions.

To compile the program, enter the following command: g++ task2_1.cpp -o a.out

To run the program, first ensure that there is an input txt file with a properly formatted game board written out. To run the program in one-move mode, enter the following command:

./a.out one-move <input> <output> <depth-value>

To run the program in interactive mode, enter the following command:

./a.out interactive <input> <computer-next/human-next> <depth-value>