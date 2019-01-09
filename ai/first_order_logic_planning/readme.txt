Name: Connor Twohey
UTA ID: 1001177282

For my Hanoi problems, I designated disk1 as the smallest, and disk5 as the largest.
This means that each fact file should characterize each disk accordingly (and also according to how
many disks are being used in the problem). So you would need to include (smaller disk1 disk2), (smaller disk2 disk3), and so on for each disk.
Also, each disk needs to be designated as "smaller" than the three pegs.
This is because the disks and the pegs are both Objects, and the computer doesn't distinguish them.
By designating the disks as smaller than the pegs, it ensures that the pegs will always remain
where they are, and won't be "moved". The code would look like this: (smaller disk1 A) (smaller disk1 B) (smaller disk1 C), and so on, for every disk used in the problem.

As long as the above information is included in the preconds section, it will work with my hanoi_ops.txt file (assuming that the initial state is in preconds and the goal state is in the effects section). The fact files that I created for the two given problems should also serve as a more-than-adequate guide.

Note (1-8-19): I have included the original assignment document. The specifications of the programming portion are included there, as are the questions for the written portion.