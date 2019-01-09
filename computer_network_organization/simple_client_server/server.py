#Connor Twohey
#cpt7282
#1001177282

#Based on skeleton code provided by Sajib Datta

#import socket module
from socket import *
import sys

#Getting server info from command line
HOST = ''
if len(sys.argv) == 2:
	PORT = sys.argv[1]
else:
	PORT = 8080

#Preparing and binding server socket
serverSocket = socket(AF_INET, SOCK_STREAM)
serverSocket.bind((HOST, int(PORT)))

#Waiting for connections
#the listen() method should handle the multithreading
serverSocket.listen(5)

if serverSocket is None:
	print 'could not open socket'
	sys.exit(1)

while True:
	#Establish the connection and retrieve connection parameters
	print 'Ready to serve...\n'
	connectionSocket, addr = serverSocket.accept()
	remoteAddr = connectionSocket.getpeername()
	connParams = getaddrinfo(remoteAddr[0], PORT)
	
	try:
		#Receiving GET Request
		message = connectionSocket.recv(1024)
		print 'Received new message:'
		print message

		#Parsing Request
		messageSplit = message.split()
		filename = messageSplit[1]
		outputdata = []

		#Opening file and preparing outputdata
		f = open(filename[1:])
		line = f.readline()
		while line:
			outputdata.append(line)
			line = f.readline()

		#Sending HTTP Headers for text/html file
		connectionSocket.send('HTTP/1.1 200 OK\n')
		connectionSocket.send('Content-Type: text/html\n')
		connectionSocket.send('\n')

		#Send the content of the requested file to the client
		for i in range(0, len(outputdata)):
			connectionSocket.send(outputdata[i]+"""""")
		f.close()
		connectionSocket.close()

		#Displaying connection parameters
		#connParams[1][i] contains parameters for the TCP connection
		#print connParams
		print 'Connection Parameters:'
		print 'Content of getpeername(): ',
		print(remoteAddr)
		print  'Client Socket Address Family: ',
		if connParams[1][0] == AF_UNSPEC:
			print 'AF_UNSPEC'
		elif connParams[1][0] == AF_UNIX:
			print 'AF_UNIX'
		elif connParams[1][0] == AF_INET:
			print 'AF_INET'
		elif connParams[1][0] == AF_INET6:
			print 'AF_INET6'
		print 'Client Socket Type: ',
		if connParams[1][1] == SOCK_STREAM:
			print 'SOCK_STREAM'
		elif connParams[1][1] == SOCK_DGRAM:
			print 'SOCK_DGRAM'
		#Protocol family and address family are the same
		#This information comes straight from Python:
		#https://docs.python.org/2/library/socket.html
		print 'Client Socket Protocol Family: ',
		if connParams[1][0] == AF_UNSPEC:
			print 'AF_UNSPEC'
		elif connParams[1][0] == AF_UNIX:
			print 'AF_UNIX'
		elif connParams[1][0] == AF_INET:
			print 'AF_INET'
		elif connParams[1][0] == AF_INET6:
			print 'AF_INET6'
		print 'Client Socket Transfer Protocol: ',
		if connParams[1][1] == SOCK_STREAM:
			print 'TCP'
		if connParams[1][1] == SOCK_DGRAM:
			print 'UDP'

	except IOError:
		#Send response message for file not found
		header = 'HTTP/1.1 404 Not Found'
		connectionSocket.send(header)

		#Close client socket
		connectionSocket.close()
serverSocket.close()
