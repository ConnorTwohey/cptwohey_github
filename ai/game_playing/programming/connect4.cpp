#include <stdio.h>
#include <math.h>
#include <stdlib.h>
#include <time.h>
#include <string.h>
#include <iostream>
#include <vector>
#include <limits>

using namespace std;

class gameStatus
{
/*private:
  long * gameData;*/

public:
  long gameBoard[6][7];
  long currentTurn;
  int player1Score;
  int player2Score;
  int pieceCount;
  FILE *gameFile;

  gameStatus()
  {
    //gameData = new long[42];
    /*gameBoard[0] = &(gameData[0]);
    gameBoard[1] = &(gameData[7]);
    gameBoard[2] = &(gameData[14]);
    gameBoard[3] = &(gameData[21]);
    gameBoard[4] = &(gameData[28]);
    gameBoard[5] = &(gameData[35]);*/

    int i;
    for (i = 0; i < 6; i++)
    {
      for(int j=0; j<7; j++) {
        gameBoard[i][j] = 0;
      }
    }

    currentTurn = 1;
    player1Score = 0;
    player2Score = 0;
    pieceCount = 0;
    gameFile = 0;
  }

  /*~gameStatus()
  {
    delete [] gameData;
  }*/
};

// Output current game status to console
void printGameBoard(gameStatus currentGame)
{
  int i = 0;
  int j = 0;
  printf(" -----------------\n");
  for(i = 0; i < 6; i++)
  {
    printf(" | ");
    for(j = 0; j < 7; j++)
    {
      printf("%li ", currentGame.gameBoard[i][j]);
    }
    printf("| \n");
  }
  printf(" -----------------\n");
}

// Output current game status to file
void printGameBoardToFile(gameStatus currentGame)
{
	int i = 0;
	int j = 0;
	for(i = 0; i < 6; i++)
	{
		for(j = 0; j < 7; j++)
		{
			fprintf(currentGame.gameFile, "%li", currentGame.gameBoard[i][j]);
		}
		fprintf(currentGame.gameFile, "\r\n");
	}
	fprintf(currentGame.gameFile, "%li\r\n", currentGame.currentTurn);
}

// Given a column and which player it is,
// place that player's piece in the requested column.
// If move is valid and has been made, return true.
// If move is invalid, no changes made to board, return false.
bool playPiece(int column, gameStatus &currentGame)
{
	// if column full, return false
	if(currentGame.gameBoard[0][column] != 0)
  {
    cout << "Sorry, that column is full." << endl;
		return false;
  }

  int i;
	// starting at the bottom of the board, place the piece into the
	// first empty spot
	for(i = 5; i >= 0; i--)
	{
		if(currentGame.gameBoard[i][column] == 0)
		{
			currentGame.gameBoard[i][column] = currentGame.currentTurn;
			currentGame.pieceCount++;
			return true;
		}
	}
}


vector<int> possibleMoves(gameStatus currentGame) {
  gameStatus temp;
  vector<int> moves;
  for(int m=0; m<7; m++) {
    for(int i=0; i<6; i++) {
      for(int j=0; j<7; j++) {
        temp.gameBoard[i][j] = currentGame.gameBoard[i][j];
      }
    }
    temp.currentTurn = currentGame.currentTurn;
    temp.player1Score = currentGame.player1Score;
    temp.player2Score = currentGame.player2Score;
    temp.pieceCount = currentGame.pieceCount;

    bool check = playPiece(m, temp);
    /*cout << "Possible move:" << endl;
    printGameBoard(temp);*/
    if(check==true) {
      moves.push_back(m);
    }

  }
  return moves;
}

void countScore(gameStatus &currentGame)
{
  currentGame.player1Score = 0;
  currentGame.player2Score = 0;

  //check horizontally
  int i;
  for(i = 0; i < 6; i++)
  {
		//check player 1
		if(currentGame.gameBoard[i][0] == 1 && currentGame.gameBoard[i][1] == 1
			&& currentGame.gameBoard[i][2] == 1 && currentGame.gameBoard[i][3] == 1)
			{currentGame.player1Score++;}
		if(currentGame.gameBoard[i][1] == 1 && currentGame.gameBoard[i][2] == 1
			&& currentGame.gameBoard[i][3] == 1 && currentGame.gameBoard[i][4] == 1)
			{currentGame.player1Score++;}
		if(currentGame.gameBoard[i][2] == 1 && currentGame.gameBoard[i][3] == 1
			&& currentGame.gameBoard[i][4] == 1 && currentGame.gameBoard[i][5] == 1)
			{currentGame.player1Score++;}
		if(currentGame.gameBoard[i][3] == 1 && currentGame.gameBoard[i][4] == 1
			&& currentGame.gameBoard[i][5] == 1 && currentGame.gameBoard[i][6] == 1)
			{currentGame.player1Score++;}
		//check player 2
		if(currentGame.gameBoard[i][0] == 2 && currentGame.gameBoard[i][1] == 2
			&& currentGame.gameBoard[i][2] == 2 && currentGame.gameBoard[i][3] == 2)
			{currentGame.player2Score++;}
		if(currentGame.gameBoard[i][1] == 2 && currentGame.gameBoard[i][2] == 2
			&& currentGame.gameBoard[i][3] == 2 && currentGame.gameBoard[i][4] == 2)
			{currentGame.player2Score++;}
		if(currentGame.gameBoard[i][2] == 2 && currentGame.gameBoard[i][3] == 2
			&& currentGame.gameBoard[i][4] == 2 && currentGame.gameBoard[i][5] == 2)
			{currentGame.player2Score++;}
		if(currentGame.gameBoard[i][3] == 2 && currentGame.gameBoard[i][4] == 2
			&& currentGame.gameBoard[i][5] == 2 && currentGame.gameBoard[i][6] == 2)
			{currentGame.player2Score++;}
	}

	//check vertically
	int j;
	for(j = 0; j < 7; j++)
	{
		//check player 1
		if(currentGame.gameBoard[0][j] == 1 && currentGame.gameBoard[1][j] == 1
			&& currentGame.gameBoard[2][j] == 1 && currentGame.gameBoard[3][j] == 1)
			{currentGame.player1Score++;}
		if(currentGame.gameBoard[1][j] == 1 && currentGame.gameBoard[2][j] == 1
			&& currentGame.gameBoard[3][j] == 1 && currentGame.gameBoard[4][j] == 1)
			{currentGame.player1Score++;}
		if(currentGame.gameBoard[2][j] == 1 && currentGame.gameBoard[3][j] == 1
			&& currentGame.gameBoard[4][j] == 1 && currentGame.gameBoard[5][j] == 1)
			{currentGame.player1Score++;}
		//check player 2
		if(currentGame.gameBoard[0][j] == 2 && currentGame.gameBoard[1][j] == 2
			&& currentGame.gameBoard[2][j] == 2 && currentGame.gameBoard[3][j] == 2)
			{currentGame.player2Score++;}
		if(currentGame.gameBoard[1][j] == 2 && currentGame.gameBoard[2][j] == 2
			&& currentGame.gameBoard[3][j] == 2 && currentGame.gameBoard[4][j] == 2)
			{currentGame.player2Score++;}
		if(currentGame.gameBoard[2][j] == 2 && currentGame.gameBoard[3][j] == 2
			&& currentGame.gameBoard[4][j] == 2 && currentGame.gameBoard[5][j] == 2)
			{currentGame.player2Score++;}
	}

	//check diagonally

	//check player 1
	if(currentGame.gameBoard[2][0] == 1 && currentGame.gameBoard[3][1] == 1
		&& currentGame.gameBoard[4][2] == 1 && currentGame.gameBoard[5][3] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][0] == 1 && currentGame.gameBoard[2][1] == 1
		&& currentGame.gameBoard[3][2] == 1 && currentGame.gameBoard[4][3] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[2][1] == 1 && currentGame.gameBoard[3][2] == 1
		&& currentGame.gameBoard[4][3] == 1 && currentGame.gameBoard[5][4] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[0][0] == 1 && currentGame.gameBoard[1][1] == 1
		&& currentGame.gameBoard[2][2] == 1 && currentGame.gameBoard[3][3] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][1] == 1 && currentGame.gameBoard[2][2] == 1
		&& currentGame.gameBoard[3][3] == 1 && currentGame.gameBoard[4][4] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[2][2] == 1 && currentGame.gameBoard[3][3] == 1
		&& currentGame.gameBoard[4][4] == 1 && currentGame.gameBoard[5][5] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[0][1] == 1 && currentGame.gameBoard[1][2] == 1
		&& currentGame.gameBoard[2][3] == 1 && currentGame.gameBoard[3][4] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][2] == 1 && currentGame.gameBoard[2][3] == 1
		&& currentGame.gameBoard[3][4] == 1 && currentGame.gameBoard[4][5] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[2][3] == 1 && currentGame.gameBoard[3][4] == 1
		&& currentGame.gameBoard[4][5] == 1 && currentGame.gameBoard[5][6] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[0][2] == 1 && currentGame.gameBoard[1][3] == 1
		&& currentGame.gameBoard[2][4] == 1 && currentGame.gameBoard[3][5] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][3] == 1 && currentGame.gameBoard[2][4] == 1
		&& currentGame.gameBoard[3][5] == 1 && currentGame.gameBoard[4][6] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[0][3] == 1 && currentGame.gameBoard[1][4] == 1
		&& currentGame.gameBoard[2][5] == 1 && currentGame.gameBoard[3][6] == 1)
			{currentGame.player1Score++;}

	if(currentGame.gameBoard[0][3] == 1 && currentGame.gameBoard[1][2] == 1
		&& currentGame.gameBoard[2][1] == 1 && currentGame.gameBoard[3][0] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[0][4] == 1 && currentGame.gameBoard[1][3] == 1
		&& currentGame.gameBoard[2][2] == 1 && currentGame.gameBoard[3][1] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][3] == 1 && currentGame.gameBoard[2][2] == 1
		&& currentGame.gameBoard[3][1] == 1 && currentGame.gameBoard[4][0] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[0][5] == 1 && currentGame.gameBoard[1][4] == 1
		&& currentGame.gameBoard[2][3] == 1 && currentGame.gameBoard[3][2] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][4] == 1 && currentGame.gameBoard[2][3] == 1
		&& currentGame.gameBoard[3][2] == 1 && currentGame.gameBoard[4][1] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[2][3] == 1 && currentGame.gameBoard[3][2] == 1
		&& currentGame.gameBoard[4][1] == 1 && currentGame.gameBoard[5][0] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[0][6] == 1 && currentGame.gameBoard[1][5] == 1
		&& currentGame.gameBoard[2][4] == 1 && currentGame.gameBoard[3][3] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][5] == 1 && currentGame.gameBoard[2][4] == 1
		&& currentGame.gameBoard[3][3] == 1 && currentGame.gameBoard[4][2] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[2][4] == 1 && currentGame.gameBoard[3][3] == 1
		&& currentGame.gameBoard[4][2] == 1 && currentGame.gameBoard[5][1] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[1][6] == 1 && currentGame.gameBoard[2][5] == 1
		&& currentGame.gameBoard[3][4] == 1 && currentGame.gameBoard[4][3] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[2][5] == 1 && currentGame.gameBoard[3][4] == 1
		&& currentGame.gameBoard[4][3] == 1 && currentGame.gameBoard[5][2] == 1)
			{currentGame.player1Score++;}
	if(currentGame.gameBoard[2][6] == 1 && currentGame.gameBoard[3][5] == 1
		&& currentGame.gameBoard[4][4] == 1 && currentGame.gameBoard[5][3] == 1)
			{currentGame.player1Score++;}

	//check player 2
	if(currentGame.gameBoard[2][0] == 2 && currentGame.gameBoard[3][1] == 2
		&& currentGame.gameBoard[4][2] == 2 && currentGame.gameBoard[5][3] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][0] == 2 && currentGame.gameBoard[2][1] == 2
		&& currentGame.gameBoard[3][2] == 2 && currentGame.gameBoard[4][3] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[2][1] == 2 && currentGame.gameBoard[3][2] == 2
		&& currentGame.gameBoard[4][3] == 2 && currentGame.gameBoard[5][4] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[0][0] == 2 && currentGame.gameBoard[1][1] == 2
		&& currentGame.gameBoard[2][2] == 2 && currentGame.gameBoard[3][3] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][1] == 2 && currentGame.gameBoard[2][2] == 2
		&& currentGame.gameBoard[3][3] == 2 && currentGame.gameBoard[4][4] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[2][2] == 2 && currentGame.gameBoard[3][3] == 2
		&& currentGame.gameBoard[4][4] == 2 && currentGame.gameBoard[5][5] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[0][1] == 2 && currentGame.gameBoard[1][2] == 2
		&& currentGame.gameBoard[2][3] == 2 && currentGame.gameBoard[3][4] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][2] == 2 && currentGame.gameBoard[2][3] == 2
		&& currentGame.gameBoard[3][4] == 2 && currentGame.gameBoard[4][5] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[2][3] == 2 && currentGame.gameBoard[3][4] == 2
		&& currentGame.gameBoard[4][5] == 2 && currentGame.gameBoard[5][6] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[0][2] == 2 && currentGame.gameBoard[1][3] == 2
		&& currentGame.gameBoard[2][4] == 2 && currentGame.gameBoard[3][5] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][3] == 2 && currentGame.gameBoard[2][4] == 2
		&& currentGame.gameBoard[3][5] == 2 && currentGame.gameBoard[4][6] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[0][3] == 2 && currentGame.gameBoard[1][4] == 2
		&& currentGame.gameBoard[2][5] == 2 && currentGame.gameBoard[3][6] == 2)
			{currentGame.player2Score++;}

	if(currentGame.gameBoard[0][3] == 2 && currentGame.gameBoard[1][2] == 2
		&& currentGame.gameBoard[2][1] == 2 && currentGame.gameBoard[3][0] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[0][4] == 2 && currentGame.gameBoard[1][3] == 2
		&& currentGame.gameBoard[2][2] == 2 && currentGame.gameBoard[3][1] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][3] == 2 && currentGame.gameBoard[2][2] == 2
		&& currentGame.gameBoard[3][1] == 2 && currentGame.gameBoard[4][0] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[0][5] == 2 && currentGame.gameBoard[1][4] == 2
		&& currentGame.gameBoard[2][3] == 2 && currentGame.gameBoard[3][2] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][4] == 2 && currentGame.gameBoard[2][3] == 2
		&& currentGame.gameBoard[3][2] == 2 && currentGame.gameBoard[4][1] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[2][3] == 2 && currentGame.gameBoard[3][2] == 2
		&& currentGame.gameBoard[4][1] == 2 && currentGame.gameBoard[5][0] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[0][6] == 2 && currentGame.gameBoard[1][5] == 2
		&& currentGame.gameBoard[2][4] == 2 && currentGame.gameBoard[3][3] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][5] == 2 && currentGame.gameBoard[2][4] == 2
		&& currentGame.gameBoard[3][3] == 2 && currentGame.gameBoard[4][2] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[2][4] == 2 && currentGame.gameBoard[3][3] == 2
		&& currentGame.gameBoard[4][2] == 2 && currentGame.gameBoard[5][1] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[1][6] == 2 && currentGame.gameBoard[2][5] == 2
		&& currentGame.gameBoard[3][4] == 2 && currentGame.gameBoard[4][3] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[2][5] == 2 && currentGame.gameBoard[3][4] == 2
		&& currentGame.gameBoard[4][3] == 2 && currentGame.gameBoard[5][2] == 2)
			{currentGame.player2Score++;}
	if(currentGame.gameBoard[2][6] == 2 && currentGame.gameBoard[3][5] == 2
		&& currentGame.gameBoard[4][4] == 2 && currentGame.gameBoard[5][3] == 2)
			{currentGame.player2Score++;}
}

// Player 1 scores a point: +1
// Player 2 scores a point: -1
// This acts as the evaluation function.
int calculateUtilityValue(gameStatus g) {
  countScore(g);
  int r;
  r = g.player1Score - g.player2Score;
  return r;
}

int alphabeta(gameStatus g, int alpha, int beta, int depth, int currentLevel) {

  if(currentLevel==depth-1) {
    int uValue = calculateUtilityValue(g);
    return uValue;
  }

  vector<int> moves = possibleMoves(g);

  int player = g.currentTurn;
  for(int k=0; k<moves.size(); ++k) {

    gameStatus game;
    for(int i=0; i<6; i++) {
      for(int j=0; j<7; j++) {
        game.gameBoard[i][j] = g.gameBoard[i][j];
      }
    }
    game.currentTurn = g.currentTurn;
    game.player1Score = g.player1Score;
    game.player2Score = g.player2Score;
    game.pieceCount = g.pieceCount;
    playPiece(moves[k], game);

    if(alpha>=beta) {
      if(player==1) return alpha;
      else
        return beta;
    }
    if(player==1) {
      int val = alphabeta(game, alpha, beta, depth, currentLevel+1);
      if(val>alpha) alpha=val;
    }
    else {
      int val = alphabeta(game, alpha, beta, depth, currentLevel+1);
      if(val<beta) beta=val;
    }
  }

  if(player==1) return alpha;
  else
    return beta;
}


int chooseMove(gameStatus g, int depth) {
  int alpha = -100;
  int beta = 100;


  vector<int> moves = possibleMoves(g);

  int move = moves[0];
  for(int k=0;k<moves.size();k++) {
    if(alpha>=beta) {
      return move;
    }

    gameStatus game;
    for(int i=0; i<6; i++) {
      for(int j=0; j<7; j++) {
        game.gameBoard[i][j] = g.gameBoard[i][j];
      }
    }
    //cout << "Setting other variables" << endl;
    game.currentTurn = g.currentTurn;
    game.player1Score = g.player1Score;
    game.player2Score = g.player2Score;
    game.pieceCount = g.pieceCount;

    int val = alphabeta(game, alpha, beta, depth, 1);
    if(val>alpha) {
      move = moves[k];
      alpha = val;
    }
  }

  return move;
}

void aiPlay(gameStatus &currentGame, int depth)
{
	int column = chooseMove(currentGame, depth);
	int result = 0;
	result = playPiece(column, currentGame);
  printf("\n\nmove %d: Player %li, column %d\n",
         currentGame.pieceCount, currentGame.currentTurn, column);
	if(currentGame.currentTurn == 1)
		currentGame.currentTurn = 2;
	else if (currentGame.currentTurn == 2)
		currentGame.currentTurn = 1;
}

int main(int argc, char ** argv)
{
  char ** command_line = argv;

  if (argc != 5)
  {
    printf("Four command-line arguments are needed:\n");
    printf("Usage: %s interactive [input_file] [computer-next/human-next] [depth]\n", command_line [0]);
    printf("or:  %s one-move [input_file] [output_file] [depth]\n", command_line [0]);

    return 0;
  }

  char * game_mode = command_line [1];

  if (strcmp (game_mode, "one-move") != 0 &&
      strcmp(game_mode, "interactive") != 0)
  {
    printf("%s is an unrecognized game mode\n", game_mode);
    return 0;
  }


  char * input = command_line[2];
  char * depthC = command_line[4];
  int depth;
  sscanf(depthC,"%d",&depth);

  gameStatus currentGame;	 // Declare current game
  printf("\nMaxConnect-4 game\n");

  currentGame.gameFile = fopen(input, "r");
  printf("game state before move:\n");

  //set currentTurn
  char current = 0;
  int i, j;
  if (currentGame.gameFile != 0)
  {
    for(i = 0; i < 6; i++)
    {
      for(j = 0; j < 7; j++)
      {
        do
        {
          current = getc(currentGame.gameFile);
        }
        while ((current == ' ') || (current == '\n') || (current == '\r'));

        currentGame.gameBoard[i][j] = current - 48;
        if(currentGame.gameBoard[i][j] > 0)
        {
         currentGame.pieceCount++;
        }
      }
    }

    do
    {
      current = getc(currentGame.gameFile);
    }
    while ((current == ' ') || (current == '\n') || (current == '\r'));

    currentGame.currentTurn = current - 48;
    fclose(currentGame.gameFile);
  }

  printGameBoard(currentGame);
  countScore(currentGame);
  printf("Score: Player 1 = %d, Player 2 = %d\n\n", currentGame.player1Score, currentGame.player2Score);

  if(strcmp(game_mode, "interactive")==0) {
    char * turn = command_line[3];
    while(currentGame.pieceCount < 42) {

      if(strcmp(turn, "computer-next")==0) {
        aiPlay(currentGame, depth);
        currentGame.gameFile = fopen("computer.txt", "w");
        printGameBoard(currentGame);
        printGameBoardToFile(currentGame);
        int column;
        cout << "Please enter a column that you'd like to play a piece: ";
        cin >> column;
        bool check=false;
        while(check==false) {
          if(currentGame.pieceCount == 42)
            break;
            check = playPiece(column-1, currentGame);
            if(check==false) {
              cout << "That column was full. Please enter another column: ";
              cin >> column;
            }
          }
          currentGame.gameFile = fopen("human.txt", "w");
          printGameBoard(currentGame);
          printGameBoardToFile(currentGame);
          currentGame.currentTurn = 1;
        }
        else {
          currentGame.currentTurn = 2;
          int column;
          cout << "Please enter a column that you'd like to play a piece: ";
          cin >> column;
          bool check=false;
          while(check==false) {
            if(currentGame.pieceCount == 42)
              break;
              check = playPiece(column-1, currentGame);
              if(check==false) {
                cout << "That column was full. Please enter another column: ";
                cin >> column;
              }
            }

            //printGameBoard(currentGame);


            currentGame.gameFile = fopen("human.txt", "w");
            printGameBoard(currentGame);
            printGameBoardToFile(currentGame);

            currentGame.currentTurn = 1;

            aiPlay(currentGame, depth);
            currentGame.gameFile = fopen("computer.txt", "w");
            printGameBoard(currentGame);
            printGameBoardToFile(currentGame);
        }
      }
    }

  if(strcmp(game_mode, "one-move")==0) {
    char * output = command_line[3];
  // Seed random number generator
    int seed = time(NULL);
    srand(seed);

    if(currentGame.pieceCount == 42)
    {
      printf("\nBOARD FULL\n");
      printf("Game over!\n\n");

      return 1;
    }


    aiPlay(currentGame, depth);
    printf("game state after move:\n");
    printGameBoard(currentGame);
    countScore(currentGame);
    printf("Score: Player 1 = %d, Player 2 = %d\n\n", currentGame.player1Score, currentGame.player2Score);

    currentGame.gameFile = fopen(output, "w");
    if (currentGame.gameFile != 0)
    {
      printGameBoardToFile(currentGame);
      fclose(currentGame.gameFile);
    }
    else
    {
      printf("error: could not open output file %s\n", output);
    }

    cout << "End of program (for one-move)" << endl;
    return 1;
  }
}
