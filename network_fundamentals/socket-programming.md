# Socket Programming with Python

- Recall that network applications usually consist of two programs: the **server** program and the **client** program. These programs reside on two separate end systems.
- When any of these programs want to communicate with another, they write the data they want to send to their **sockets**. The underlying protocols then deliver the data to the appropriate destination.

## Types of Network Applications

- **Standard** : applications that use well-known protocols based on meticulous standards laid down by standard documents.
  - Pros: any othe developer can write applications that are compatible with standard ones.
  - Cons: some customizability will be compromised.
- **Proprietary** : applications that use protocols of the developer's own design. The source code for such applications is not generally disclosed.
  - Pros: extremely customizable, which allows for optimizing the application for particular use cases.
  - Cons: it's incredible difficult to write applications compatible with a propriety one; also, designing protocols can lead to security loopholes.

## Socket Programming

Remember that sockets are just software endpoints that processes write and read data from. They are bound to an IP address and a port. The sending process attaches the IP address and port number of the receiving application. The IP address and port number of the sending process are also attached to the packets as headers, but that’s not done manually in the code of the application itself. Networking libraries are provided with nearly all programming languages and they take responsibility for lots of plumbing.

### Setting up a UDP Socket

Set up a socket for a UDP server program that works like so:

1. The client will send a line of text to the server.
2. The server will receive the data and convert each character to **uppercase**.
3. The server will send the uppercase characters to the client.
4. The client will receive and display them on its screen.

Here is an example source code for setting up a socket: [setup-socket.py](resources/setup-socket.py)

#### Creating a `socket` object

```python
import socket

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
print(s)
```

The syntax is as follows:

```python
socket.socket(family, type, proto, fileno)
```

1. **Family** : The address family property is used to assign the type of addresses that a socket can communicate with. Only then can the addresses of that type be used with the socket. There are three main options available for this:
   - `AF_INET` : This family is used with **IPV4** addresses. IP addresses are most commonly used.
   - `AF_INET6` : Another address scheme, IPv6, was introduced since IPV4 is limited to about 4 billion addresses which are not sufficient, considering the exponential growth of the Internet.
   - `AF_UNIX` : This family is used for [Unix Domain Sockets (UDS)](https://en.wikipedia.org/wiki/Unix_domain_socket), an interprocess communication endpoint for the same host. It’s available on POSIX-compliant systems. Most operating systems today like Windows, Linux and Mac OS are POSIX compliant! So processes on a system can communicate with each other directly through this instead of having to communicate via the network.
2. **Type** : The type specifies the transport layer protocol:
   - `SOCK_DGRAM` specifies that the application is to use **User Datagram Protocol (UDP)**. Recall that UDP is less reliable but requires no initial connection establishment.
   - `SOCK_STREAM` specifies that the application is to use **Transmission Control Protocol (TCP)**. Recall that while TCP requires some initial setup, it’s more reliable than UDP.

If you want to study the rest of the fields, have a look at the [documentation](https://docs.python.org/3/library/socket.html).

#### Binding the Socket

```python
import socket

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
port = 3000
hostname = '127.0.0.1'
s.bind((hostname, port))
```

Now, we bind the socket to an IP address and a port using the `bind()` function. It’s given a certain `n`-tuple as an argument, where `n` depends on the family of the socket. In the case of `SOCK_DGRAM`, it’s a tuple of the IP address and the port like the following:

```python
socketObject.bind((IP address, port))
```

Note that the ports `0 - 1024` should be avoided as they're reserved for other system-defined process. Binding to them may generate an error as it may already be in use by another application. The hostname, on the other hand, is the IP address that your server will listen on. It can be set to one of the three options:

1. If you are following along on your local machine, set it to `127.0.0.1` which is the localhost address for IPv4. This address is called the loopback or localhost address.
2. It can also be set into empty string `''` which represents the `INADDR_ANY`. This specifies that the program intends to receive packets sent to the specified port destined for any of the IP addresses configured on that machine.
3. It could be set to a specific IP address assigned to a machine.

### Writing a UDP Server

The server will reply to every client’s message with a capitalized version of whatever a client program sends to it. There are other functions that such a server can perform as well. So, in particular, the server will:

1. Print the original message received from the client.
2. Capitalize the message.
3. Send the capitalized version back to the client.

Use the `getsockname()` method on an object of the `socket` class to find the current IP address and port that a socket is bound to:

```python
...
s.bind((hostname, port))
print('Listening at {}'.format(s.getsockname()))
```

See complete code here: [write-server.py](resources/write-server.py)

#### Receiving Messages from Clients

The server can now receive data from clients! The `recvfrom()` method accepts data of `MAX_SIZE_BYTES` length which is the size of one UDP datagram in bytes. This is to make sure that we receive the entirety of each packet. It also returns the IP address of the client that sent the data. We store the data and the client’s IP address in the variables `data` and `clientAddress` respectively. Note that the code stops and waits at `recvfrom()` until some data is received.

```python
import socket

MAX_SIZE_BYTES = 65535

# setup socket

while True:
    data, clientAddress = s.recvfrom(MAX_SIZE_BYTES)
```

#### Capitalizing the Data

```python
# data from recvfrom() method
message = data.decode('ascii')
upperCaseMessage = message.upper()
```

#### Printing the Client's Message and Encoding

```python
# capitalized message
print('The client at {} says {!r}'.format(clientAddress, message))
data = upperCaseMessage.encode('ascii')
```

#### Sending the Message Back to the Client

```python
# encoded client's message
s.sendto(data, clientAddress)
```

See complete code here: [write-server.py](resources/write-server.py)

### Writing a UDP Client

Instead of explicitly binding the socket to a given port and IP, let the OS take care of it using ephemeral ports. The OS will bind the socket to a port dynamically.

```python
import socket

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
print('The OS assigned the address {} to me'.format(s.getsockname()))
```

Note that the goal of this client and server was for the client to send a string to the server that it would capitalize and send back. To get that string, use `input()` method. The user can type a string of their choice and hit enter.

```python
message = input('Input lowercase sentence: ')
data = message.encode('ascii')
```

#### Send to the Server

Send the message to the server using the `sendto()` function. In addition to the data, this function takes an IP address and a port number.

```python
# data = encoded message
s.sendto(data, ('127.0.0.1', 3000))
print('The OS assigned the address {} to me'.format(s.getsockname()))
```

#### Receiving the Server's Response

```python
MAX_SIZE_BYTES = 65535

# message sent to server
data, address = s.recvfrom(MAX_SIZE_BYTES)
text = data.decode('ascii')
print('The server {} replied with {!r}'.format(address, text))
```

### Running the UDP Server and Client Together

See the complete code here: [udp.py](resources/udp.py)

To run the above code or script:

1. Run the server side first by running the command `python3 resources/udp.py server`
2. Open another terminal
3. Type the command `python3 resources/udp.py client` Note that it can be server in place of client.
4. Enter the text in the client window and see the effect.

### Fix with `connect()`

Use the `connect()` method to forbid other addresses from sending packets to the client.

```python
import socket

MAX_SIZE_BYTES = 65535 # Mazimum size of a UDP datagram

def client(port):
    host = '127.0.0.1'
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    s.connect((host, port))
    message = input('Input lowercase sentence:' )
    data = message.encode('ascii')
    s.send(data)
    print('The OS assigned the address {} to me'.format(s.getsockname()))
    data = s.recv(MAX_SIZE_BYTES)
    text = data.decode('ascii')
    print('The server replied with {!r}'.format(text))
```

With the `sendto()` method, we had to specify the IP address and port of the server every time the client wanted to send a message. However, with the `connect()` method we used, we just use `send()` and `recv()` without passing any arguments about which address to send to because the program knows that. This also means that no server other than the one the client _connected_ to can send it messages. The operating system discards any of those messages by default.

The main disadvantage of this method is that the **client can only be connected to one server at a time**. In most real life scenarios, singular applications connect to _multiple_ servers!

### Fix with Address Matching

A better, though more tedious approach, to handle multiple servers would be to **check the return address of each reply against a list of addresses that replies are expected from**.

```python
import socket

MAX_SIZE_BYTES = 65535 # Mazimum size of a UDP datagram

def client(port):
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    hosts = []
    while True:
        host = input('Input host address:' )
        hosts.append((host,port))
        message = input('Input message to send to server:' )
        data = message.encode('ascii')
        s.sendto(data, (host, port))
        print('The OS assigned the address {} to me'.format(s.getsockname()))
        data, address = s.recvfrom(MAX_SIZE_BYTES)
        text = data.decode('ascii')
        if(address in hosts):
            print('The server {} replied with {!r}'.format(address, text))
            hosts.remove(address)
        else:
            print('message {!r} from unexpected host {}!'.format(text, address))
```

From above code, a list called _hosts_ was created which contains tuples like `(IPaddresses, port numbers)` of any host that the client connects to. Upon receiving every message, it checks whether the message is from a host it expects to receive a reply from. As soon as a reply is received, it removes the host from the list.

### Sample Program: UDP Chat App

Here is a sample code in writing a UDP Chat App: [chat.py](resources/chat.py)

## TCP Server and Client Program

The code here, [tcp.py](resources/tcp.py), is an example of a tcp server and client program. The client program is pretty much the same as a UDP client program. There are a few key differences:

### Handling Fragmentation

#### `sendall()`

One of three things may happen at every `send()` call:

1. All the data you passed to it gets sent immediately.
2. None of the data gets transmitted.
3. Part of the data gets transmitted.

The `send()` function returns the length of the number of bytes it successfully transmitted, which can be used to check if the entire segment was sent.

Here’s what code to handle partial or no transmission would look like:

```python
bytes_sent = 0 # No bytes initially sent
while bytes_sent < len(message): # If number of bytes sent is less than the amount of data
    message_left = message[bytes_sent:] # Indexing and storing the part of the message remaining
    bytes_sent += sock.send(message_left) # Sending remaining message
```

However, `sendall()` method ensures that all of the data gets sent.

#### `recvall()`

Unfortunately, no equivalent to automatically handle fragmentation exists for the receiving end. Hence, we’d have to cater for the cases when:

1. Part of the sent data arrives
2. None of the sent data arrives

See `recvall()` method in [tcp.py](resources/tcp.py).
