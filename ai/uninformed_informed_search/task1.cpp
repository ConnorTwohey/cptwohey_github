#include <iostream>
#include <fstream>
#include <string>
#include <cstring>
#include <cstdlib>
#include <stdlib.h>
#include <vector>
#include <list>
#include <queue>
#include <algorithm>

using namespace std;

int mapSize = 50;

//The path struct and the pathComparison class
//are meant for use in a priority queue

struct pathInfo
{
  list<string> path;
  int distance;
  bool operator>(const pathInfo& rhs) const {
    return distance > rhs.distance;
  }
};

class pathComparison
{
public:
  bool operator() (pathInfo p1, pathInfo p2) const {
    return p1>p2;
  }
};

void findPath(string start, string fin, string map[][3]) {
  priority_queue<pathInfo, vector<pathInfo>, pathComparison> pq;

  int distUpperLimit;
  for(int i=0; i<mapSize; i++) {
    int temp;
    sscanf(map[i][2].c_str(), "%d", &temp);
    distUpperLimit += temp;
  }

  pathInfo pstart;
  pstart.path.push_back(start);
  pstart.distance = 0;

  pq.push(pstart);

  string current;
  while(!pq.empty()) {
    pathInfo curr = pq.top();
    pathInfo temp;
    current = pq.top().path.back();



    vector<string> adjacentCities;
    vector<int> adjacentDistance;
    for(int i=0;i<mapSize;i++) {
      int dist;
      if(map[i][0].compare(current)==0) {
        adjacentCities.push_back(map[i][1]);
        sscanf(map[i][2].c_str(),"%d", &dist);
        adjacentDistance.push_back(dist);
      }
      if(map[i][1].compare(current)==0) {
        adjacentCities.push_back(map[i][0]);
        sscanf(map[i][2].c_str(),"%d", &dist);
        adjacentDistance.push_back(dist);
      }
    }

    pq.pop();
    if(current.compare(fin) == 0) {
        cout << "A path has been found!" << endl;
        cout << "distance: " << curr.distance << " km" << endl;
        cout << "route:" << endl;
        while(curr.path.size() > 1) {
          string a = curr.path.front();
          cout << a << " to ";
          curr.path.pop_front();
          string b = curr.path.front();
          string dist;
          for(int i=0; i<mapSize; i++) {
            if((a.compare(map[i][0])==0 && b.compare(map[i][1])==0) ||
                (b.compare(map[i][0])==0 && a.compare(map[i][1])==0)) {
              dist = map[i][2];
            }
          }
          int finalDist;
          sscanf(dist.c_str(),"%d",&finalDist);
          cout << b << ", " << finalDist << " km" << endl;
        }
        exit(0);
    }
    else {
      for(int i=0; i<adjacentCities.size(); i++) {
        temp = curr;
        temp.path.push_back(adjacentCities.at(i));
        temp.distance += adjacentDistance.at(i);
        pq.push(temp);
      }
      if(curr.distance > distUpperLimit) {
        cout << "distance: infinity" << endl;
        cout << "route:" << endl;
        cout << "none" << endl;
        exit(0);
      }
    }
  }
}



int main(int argc, char* argv[]) {
  string fname = argv[1];
  string start = argv[2];
  string destination = argv[3];
  ifstream input(fname.c_str());
  if(!input) {
    cerr << "Unable to open file";
    exit(1);
  }

  string graph[mapSize][3];
  string line;
  int i=0;
  while(getline(input,line)) {
    if(line.compare("END OF INPUT\r")==0)
      break;

    char * lineC = strdup(line.c_str());

    char * tok = strtok(lineC, " ");
    graph[i][0] = string(tok);
    tok = strtok(NULL, " ");
    graph[i][1] = string(tok);
    tok = strtok(NULL, "\n");
    graph[i][2] = string(tok);


    ++i;
  }

  findPath(start, destination, graph);

}
