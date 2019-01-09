#Connor Twohey
#cpt7282
#1001177282

#imports
from socket import *
import sys
import time

#Getting server information from command line
if len(sys.argv) == 4:
	HOST = sys.argv[1] + ''
	PORT = sys.argv[2]
	filename = sys.argv[3]
else:
	HOST = sys.argv[1] + ''
	PORT = 8080
	filename = sys.argv[2]

#Preparing and connecting client socket
t1 = time.time()
clientSocket = socket(AF_INET, SOCK_STREAM)
test = clientSocket.connect((HOST, int(PORT)))
t2 = time.time()
rtt = str(t2-t1)
print 'Round Trip Time for Connection: ' + rtt + ' s'


if clientSocket is None:
	print 'could not open socket'
	sys.exit(1)

#Constructing and sending request message
#Also calculating RTT for initial GET request
req = 'GET /' + filename + ' HTTP/1.1\n'
req += 'Host: ' + HOST + ':' + PORT + '\n'
req += 'Connection: keep-alive\n'
t1 = time.time()
clientSocket.send(req)

#Receiving and checking response message
data = clientSocket.recv(1024)


if data == 'HTTP/1.1 200 OK\n':
	#Retrieving Connection Parameters:
	remoteAddr = clientSocket.getpeername()
	connParams = getaddrinfo(remoteAddr[0], PORT)

	#Preparing output file
	f = open(filename, "w+")

	#Receiving file data
	messageFound = False
	while 1:
		data = clientSocket.recv(1024)
		lines = data.splitlines()
		if not data: break
		for i in range(0, len(lines)):
			print lines[i]
			if 'Content-Type' not in lines[i] and len(lines[i].split()) != 0:
				f.write(lines[i].strip()+'\n')
	clientSocket.close()
	f.close()

	#Displaying Connection Parameters
	#connParams[1][i] contains parameters for the TCP connection
	#print connParams
	print 'Connection Parameters:'
	print 'Content of getpeername(): ',
	print(remoteAddr)
	print 'Server Socket Address Family: ',
	if connParams[1][0] == AF_UNSPEC:
		print 'AF_UNSPEC'
	elif connParams[1][0] == AF_UNIX:
		print 'AF_UNIX'
	elif connParams[1][0] == AF_INET:
		print 'AF_INET'
	elif connParams[1][0] == AF_INET6:
		print 'AF_INET6'
	print 'Server Socket Type: ',
	if connParams[1][1] == SOCK_STREAM:
		print 'SOCK_STREAM'
	elif connParams[1][1] == SOCK_DGRAM:
		print 'SOCK_DGRAM'
	#Protocol family and address family are the same
	#This information comes straight from Python:
	#https://docs.python.org/2/library/socket.html
	print 'Server Socket Protocol Family: ',
	if connParams[1][0] == AF_UNSPEC:
		print 'AF_UNSPEC'
	elif connParams[1][0] == AF_UNIX:
		print 'AF_UNIX'
	elif connParams[1][0] == AF_INET:
		print 'AF_INET'
	elif connParams[1][0] == AF_INET6:
		print 'AF_INET6'
	print 'Server Socket Transfer Protocol: ',
	if connParams[1][1] == SOCK_STREAM:
		print 'TCP'
	elif connParams[1][1] == SOCK_DGRAM:
		print 'UDP'
else:
	print data
	clientSocket.close()

t2 = time.time()
rtt = str(t2-t1)
print 'Round Trip Time for entire GET operation: ' + rtt + ' s'