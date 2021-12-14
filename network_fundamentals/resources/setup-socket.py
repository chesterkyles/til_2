# importing socket
import socket

# creating a socket object
s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
print(s)

# binding the socket
port = 3000            # note that 0-1024 ports are reserved, so avoid
hostname = '127.0.0.1' # localhost IPv4 address
s.bind((hostname, port))
