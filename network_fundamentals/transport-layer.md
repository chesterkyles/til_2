# Transport Layer

## Key Responsibilities

- **Extends network to the applications** : the transport layer takes messages from the network to applications. In other words, while the network layer (directly below this layer) transports messages from one end-system to another, the transport layer delivers the message to and from the relevant application _on_ an end-system.
- **Logical application-to-application delivery** : the transport layer makes it so that applications can address other applications on other end-systems directly. This is true even if it exists halfway across the world. So it provides a layer of **abstraction**.
- **Segments data** : The transport later also divides the data into manageable pieces called 'segments' or 'datagrams'.
- **Can allow multiple conversations** : Tracks each application to application connection or 'conversation' separately, which can allow multiple conversations to occur at once.
- **Multiplexes and demultiplexes data** : Ensures that the data reaches the relevant application _within_ an end-system. So if multiple packets get sent to one host, each will end up at the correct application.

## Transport Layer Protocols

The transport layer has two prominent protocols: the **transmission control protocol** and the **user datagram protocol**.

TCP | UDP
----| ---
Delivers messages that we call ‘segments’ reliably and in order. | Does not ensure in-order delivery of messages that we call ‘datagrams.’
Detects any modifications that may have been introduced in the packets during delivery and corrects them. | Detects any modifications that may have been introduced in the packets during delivery but does not correct them by default.
Handles the volumes of traffic at one time within the network core by sending only an appropriate amount of data at one time. | Does not ensure reliable delivery. Generally faster than TCP because of the reduced overhead of ensuring uncorrupted delivery of packets in order.
Examples of applications/application protocols that use TCP are: **HTTP**, **E-mail**, **File Transfers**. | Applications that use UDP include: **Domain Name System (DNS)**, **live video streaming**, and **Voice over IP (VoIP)**.

## Multiplexing and Demultiplexing

End-systems typically run a variety of applications at the same time. For example, at any given time a browser, a music streaming service, and an email agent could be running. So how does the end-system know which process to deliver packets to? Well, that's where the transport layer's demultiplexing comes in.

**Demultiplexing** is the process of delivering the correct packets to the correct applications from one stream.

**Multiplexing** allows messages to be sent to more than one destination host via a single medium.

Multiplexing and demultiplexing are usually a concern when one protocol (TCP for example) is used by many others (HTTP, SMTP, FTP) in an upper layer.

### How Do They Work?

Recall that **sockets** are gateways between applications and the network, i.e., if an application wants to send something over to the network, it will write the message to its socket. Sockets have an associated **port number** with them.

- Port numbers are 16-bit long and range from 0 and 65,535
- The first 1023 ports are reserved for certain applications and are called well-known ports. For example, port 80 is reserved for HTTP.

The transport layer **labels** packets with the port number of the application a message is from and the one it is addressed to. This is what allows the layer to multiplex and demultiplex data.

### Ports

- **Sockets**, which are gateways to applications, are identified by a combination of an **IP address** and a 16-bit **port number**. That means `2^16 = 65536` port numbers exist. However, they start from port 0 so they exist in the range of `0 - 65536`.
- Out of these, the port numbers `0 - 1023` are **well-known** and are reserved for certain standard protocols.
- Refer to page 16 of [RFC 1700](https://tools.ietf.org/pdf/rfc1700.pdf) for more details regarding what port number is assigned to what protocol.

## User Datagram Protocol (UDP)

- When a datagram is sent out from an application, the port number of the associated **source** and **destination** application is appended to it in the UDP header.
- When the datagram is received at the receiving host, it sends the datagram off to the relevant application's socket based on the **destination port number**.
- If the source port and source IP address of two datagrams are different but the destination port and IP address are the same, the datagrams will still get sent to the same application.

### On Port Assignment in UDP

It’s far more common to let the port on the client-side of an application be assigned dynamically instead of choosing a particular port. This is because for communication, both parties must be able to identify each other. Since the client initiates communication to the server, it must know the port number of the application on the server. However, the server doesn’t need to know the client application’s port number in advance. When the first datagram from the client reaches the server, it will carry the client port number, which the server can use to send datagrams back to the client.

However, server-side applications generally do not use dynamically allocated ports! This is because they are running well-known protocols like HTTP and need to be bound to specific ports.

## Congestion Control

When more packets than the network has bandwidth for are sent through, some of them start getting dropped and others get delayed. This phenomenon leads to an overall drop in performance and is called **congestion**.

Congestion physically occurs at the network layer, i.e. in routers, however, it's mainly caused by the transport layer sending too much data at once. That means it will have to be dealt with or 'controlled' at the transport layer as well.

Congestion control is really just congestion avoidance. Here's how the transport layer controls congestion:

1. It sends packets at a slower rate in response to congestion.
2. The 'slower rate' is still fast enough to make efficient use of the available capacity.
3. Changes in the traffic are also kept track of.

## Bandwidth Allocation Principles

Question: Should bandwidth be allocated to each host or to each _connection_ made by a host?

Not all hosts are created equal; Some can send and receive at a higher data rate than others. Furthermore, if the bottleneck bandwidth was allocated equally to all hosts, some of them wouldn’t be able to use the bandwidth to its full capacity and some wouldn’t have enough. For example, if an Internet-enabled doorbell and a busy server had the same bandwidth, the doorbell would have too much and the server would likely not have enough. The per-connection allocation, on the other hand, can be exploited by hosts opening multiple connections to the same end-system. Usually, bandwidth is allocated per connection.

### Efficiency and Power

- Bandwidth cannot be divided and allocation equally amonst end-systems because real traffic is transmitted in **bursts** and not in one continuous stream. Simultaneous bursts of traffic from all end-systems can cause more than the allocated bandwidth to be used which results in congestion and a consequent drop in performance.
- Congestion occurs _before_ the maximum capacity of the network is reached and **congestion collapse** occurs as it's approach. Congestion collapse occurs when all end-systems are sending a lot of traffic but nothing is being received, for example, when all or most packets are dropped. There a few causes for this, including but not limited to Spurious retransmissions.

## Max-min Fairness

Usually, bottleneck links are wide-area links that are much more expensive to upgrade than the local area networks. Mathematically, the control scheme should ensure that the sum of the transmission rate allocated to all hosts at any given time should be approximately equal to the bottleneck link’s bandwidth.

Furthermore, the congestion control scheme should be fair. Most congestion schemes aim at achieving **max-min fairness**. An allocation of transmission rates to sources is said to be **max-min fair** if:

1. No link in the network is congested
2. The rate allocated to a source _j_ cannot be increased without decreasing the rate allocated to another source _i_, whose allocation is smaller than the rate allocated to the source _j_.

In other words, this principle postulates that **increasing the transmission rate of one end-system necessarily decreases the transmission rate allocated to another end-system with an equal or smaller allocation**.

## Network Layer Imperfections

The transport layer must deal with the imperfections of the network layer service. There are three types of imperfections that must be considered by the transport layer:

1. Segments can be **corrupted** by transmission errors
2. Segments can be **lost**
3. Segments can be **reordered** or **duplicated**

### Checksum

The first imperfection of the network layer is that segments **may be corrupted by transmission errors**. The simplest error detection scheme is the **checksum**.

A checksum can be based on a number of schemes. One possible scheme is an arithmetic sum of all the bytes of a segment. Checksums are computed by the sender and attached with the segment. The receiver verifies it upon reception and can choose what to do in case it is not valid. Quite often, the segments received with an invalid checksum are **discarded**.

### Retransmission Timers

The second imperfection of the network layer is that **segm?ents may be lost**. Since the receiver sends an acknowledgment segment after having received each data segment, the simplest solution to deal with losses is to use a **retransmission timer**.

A retransmission timer starts when the sender sends a segment. The value of this retransmission timer should be greater than the **round-trip-time**, for example, the delay between the transmission of a data segment and the reception of the corresponding acknowledgment. When the retransmission timer expires, the sender assumes that the data segment has been lost and retransmits it.

Unfortunately, retransmission timers alone are not sufficient to recover from segment losses. Let us consider the situation where an acknowledgment is lost. In this case, the sender retransmits a data segment that has been received correctly, but not properly acknowledged.

### Sequence Numbers

To identify duplicates, transport protocols associate an identification number with each segment called the **sequence number**. This sequence number is prepended to the segments and sent. This way, the end entity can identify duplicates.

## UDP (User Datagram Protocol)

UDP is a transport layer protocol that works over the network layer's famous Internet Protocol. [RFC 768](https://tools.ietf.org/pdf/rfc768.pdf) is the official RFC for UDP.

UDP does not involve any initial handshaking like TCP does, and is hence called a **connectionless** protocol. This means that there are no established ‘connections’ between hosts. UDP prepends the **source and destination ports** to messages from the application layer and hands them off to the network layer. The Internet Protocol of the network layer is a **best-effort** attempt to deliver the message. This means that the message:

1. May or may not get delivered
2. May get delivered with changes in it
3. May get delivered out of order

### Structure

#### Header

UDP prepends **four** 2-byte header fields to the data it receives from the application layer. So in total, a UDP header is **8 bytes** long. The fields are:

1. Source port number
2. Destination port number
3. Length of the datagram (header and data in bytes)
4. Checksum to detect if errors have been introduced into the message

#### Data

Other than the headers, a UDP datagram contains a body of data which can be up to 65,528 bytes long. The nature of the data depends on the overlying application. So if the application is querying a DNS server, it would contain bytes of a zone file.

## Checksum Calculation

UDP detects if any changes were introduced into a message while it traveled over the network. To do so, it appends a _checksum_ to the packet as a field that can be checked against the message itself to see if it was corrupted. It's calculated the same way as in TCP:

1. The payload and some of the headers are divided into 16-bit words.
2. These words are then added together, wrapping any overflow around.
3. Lastly, the one's complement of the resultant sum is taken and appended to the message as the checksum.

At the receiving end, UDP sums the message in 16-bit words and adds the sum to the sent checksum. If the result is `1111111111111111`, the message was not corrupted. If the result is otherwise, it was.

If the checksum itself gets corrupted, UDP will assume that the message has an error.

### Why UDP

1. UDP can be faster. Some applications cannot tolerate the load of the retransmission mechanism of TCP, the other transport layer protocol.
2. Reliability can be built on top of UDP. TCP ensures that every message is sent by resending it if necessary. However, this reliability can be built in the application itself.
3. UDP gives finer control over what message is sent and when it is sent. This can allow the application developer to decide what messages are important and which do not need concrete reliability.
4. UDP allows custom protocols to be built on top of it. In fact, Google’s transport layer protocol, **Quick UDP Internet Connections (QUIC)**, _pronounced quick_, is an experimental transport layer network protocol built on top of UDP and designed by Google. The overall goal is to reduce latency compared to that of TCP. It’s used by most connections from the Chrome web browser to Google’s servers!
5. With the significantly smaller header gives UDP an edge over TCP in terms of reduced transmission overhead and quicker transmission times.

Network management and network monitoring is done using a protocol called Simple Network Management Protocol and it runs on UDP as well. DNS also uses UDP! In the case of failed message delivery, DNS either resends the message, sends the message to some other server, or gives a failure message.

## `tcpdump`

Link: <https://www.tcpdump.org/manpages/tcpdump.1.html>

`tcpdump` is a command-line tool that can be used to view packets being sent and received on a computer. The simplest way to run it is to simply type the following command into a terminal and hit enter. Packets will start getting prinmted rapidly to give a comprehensive view of the traffic.

However, some might not find it to be very helpful because it does not allow for a more **zoomed-in and fine-grained dissection of the packets**, which is the main purpose of tcpdump (it’s technically a packet analyzer). So you might want to consider using some flags to filter relevant packets out.

### Saving output to a file

```sh
tcpdump -w filename .ext
```

Note that `.pcap` files are used to store the packet data of a network. Packet analysis programs such as Wireshark (think of it like tcpdump with a GUI) export and import packet captures in pcap files.

### Counting packets

```sh
tcpdump -w output.pcap -c 10
```

Sample output can be found here: [ouptut.pcap](resources/output.pcap)

### Printing PCAP Files

```sh
tcpdump -w output.pcap -c 10
tcpdump -r output.pcap
```

### Looking at Real UDP Packet Headers

```sh
tcpdump udp -X -c 1
```

The `-X` flag just prints the payload of the packet (the data) in both hex and ASCII.

## Transmission Control Protocol (TCP)

TCP is what makes most modern applications as enjoyable and reliable as they are. TCP is a robust protocol meant to adapt to a diverse range of network topologies, bandwidths, delays, message sizes, and other varying factors that exist in the network layer.

TCP is a **connection-oriented protocol** unlike UDP. The connection orientedness is like a **phone call** because a connection is established before communication takes place, and then we hang up. Here are some key responsibilities of the protocol:

1. **Send data** at an appopriate transmission rate. It should be a fast enough rate to make full use of the available capacity but it shouldn't be so fast as to cause congestion.
2. **Segment data** : The application layer sends the transport layer a continuous and unsegmented stream of data so that there's no limit to how much data the application layer can give to the transport layer at once. Hence, the transport layer divides it inot appropriately sized **segments**.Note that a segment is a collection of bytes. Furthermore, when a TCP segment is too big, the network layer may break it into multiple network layer messages, so the receiving TCP entity would have to re-assemble the network layer messages.
3. **End to end flow control** : Flow control means **not overwhelming the receiver**. It’s not the same as congestion control. Congestion control tries not to choke the network. However, if the receiving machine is slow, it might drown in data even if the network is not choked. Avoiding drowning the receiver in data is end to end flow control. There is also hop by hop flow control, which is done at the data link layer.
4. **Identify and retransmit messages** that do not get delivered. The network layer cannot be relied upon to deliver messages.
5. **Identify when messages are received out of order and reassemble them**. The network layer can also not be relied upon to transmit messages in order.

### Well-Known Applications That Use TCP

- **File Transfer** : **FTP** or **File Transfer Protocol** is built on top of TCP. It uses ports **20** and **21**. When transferring files, we wouldn’t want some bytes of the file completely missing, or some chunks in the file re-ordered or some byte values changed during transfer. That’s why TCP is a natural choice for FTP. In other words, it uses TCP for its **reliability**, which is a key part of file transfer.
- **Secure Shell `SSH`** : SSH or Secure Shell is a protocol to allow a secure connection to a remote host over an unsecured network. It’s widely popular and most programmers use it to date to execute operating system shell commands on remote servers. The reasons that this application uses TCP is similar to FTP, and that’s reliable delivery.
- **Email** : All email protocols, SMTP, IMAP, and POP use TCP to ensure complete and reliable message delivery similar to the reasons that FTP uses TCP.
- **Web Browsing** : Web browsing on both HTTP and HTTPS is done on TCP as well for the same reasons as FTP.

### Key Features of TCP

- **Connection Oriented** : TCP itself is connection-oriented and creates a long term connection between hosts. The connection remains until a certain termination procedure is followed.
- **Full Duplex** : TCP is full-duplex, which means that both hosts on a TCP connection can send messages to each other simultaneously.
- **Point-to-Point Trasmission** : TCP connections have exactly two endpoints! This means that broadcasting or multicasting is not possible with TCP.
- **Error Control** : TCP can detect errors in segments and make corrections to them.
- **Flow Control** : TCP on the sending side controls the amount of data being sent at once based on the receiver’s specified capacity to accept and process it. The sender adjusts the sending rate accordingly.
- **Congestion Control** : TCP has in-built mechanisms to control the amount of congestion on the network.

### TCP Segment Header

TCP headers play a crucial role in the implementation of the protocol. In fact, TCP segments without actual data and with headers are completely valid. They’re actually used quite often. The size of the headers range from **20 - 60** bytes.

#### Source and Destination Ports

The source port is the port of the socket of the application that is sending the segment and the destination port is the port of the socket of the receiving application. The size of each field is two bytes.

#### Sequence Number

Every byte of the TCP segment’s data is labeled with a number called a **sequence number**. The sequence number field in the header has the sequence number of the first byte of data in the segment.

#### Acknowledgement Number

The acknowledgment number is a 4-byte field that represents the sequence number of the next expected segment that the sender will send or the receiver will receive.

#### Header Length

The length of the TCP header is specified here. This helps the receiving end to identify where the header ends and the data starts from.

#### Reserved Field

The header has a 4-bit field that is reserved and is always set to 00. This field aligns the total header size to be in multiples of 4 (as we saw was necessary for the header length to be processed).

#### Flags

| CWR | ECN | URG | ACK | PSH | RST | SYN | FIN |
| --- | --- | --- | --- | --- | --- | --- | --- |

There are eight flags used in TCP headers as shown above. `ACK`, `RST`, `SYN`, and `FIN` are used in the establishment, maintenance, and tear-down of a TCP connection.

- `ACK` : This flag is set to `1` in a segment to **acknowledge** a segment that was received previously. This is an important part of the protocol. In other words, when a receiver wants to acknowledge some received data, it sends a TCP segment with the ACK flag and the acknowledgment number field appropriately set. This flag is also used in connection establishment and termination.
- `RST` : The **reset** flag immediately terminates a connection. This is sent due to the result of some confusion, such as if the host doesn’t recognize the connection, if the host has crashed, or if the host refuses an attempt to open a connection.
- `SYN` : The **synchronization** flag initiates a connection establishment with a new host.
- `FIN` : This flag is used to terminate or **finish** a connection with a host.
- `CWR` and `ECN` : These flags, **Congestion Window Reduced** and **Explicit Congestion Notification** are used to handle congestion. To put it very simply, the ECN flag is set by the receiver, so that the sender knows that congestion is occurring. The sender sets the CWR flag in response to this so that the receiver knows that the receiver has reduced its congestion window to compensate for congestion and the sender is sending data at a slower rate.
- `PSH` : The default behavior of TCP is in the interest of efficiency; if multiple small TCP segments were received, the receiving TCP will combine them before handing them over to the application layer. However, when the **Push** (PSH) flag is set, the receiving end immediately flushes the data from its buffer to the application instead of waiting for the rest of it to arrive.
- `URG` : The **Urgent** flag marks some data within a message as urgent. Upon receipt of an urgent segment, the receiving host forwards the urgent data to the application with an indication that the data is marked as urgent by the sender. The rest of the data in the segment is processed normally.

#### Windows Size

The window size is essentially the amount of available space in the buffer. TCP at the receiving end buffers incoming data that has not been processed yet by the overlaying application. The amount of available space in this buffer is specified by the window size.

To put it another way, the window size is at first equal to as much data as the receiving entity is willing and able to receive. As it receives some more data, the window size will decrease and as it hands over some of the received data to the application layer, the window size will increase. This is useful to implement flow control.

#### Checksum Header

The **checksum** is calculated exactly like in UDP except that the checksum calculation is mandatory in TCP!

#### Urgent Pointer

The **urgent pointer** defines the byte to the point of which the urgent data exists. This is because a single segment can contain both parts of urgent and regular data. This field is only used in conjunction with the urgent flag.

#### Options & Padding

The **options and padding** field provides up to an **extra 40 bytes** to build extra facilities that are not covered by the regular header. The options can vary in length and exist in multiples of 3232 bytes using zeros to pad in any extra bits.

Common options are:

- `MSS` : Defines the maximum-sized payload a host can handle at one time
- `Timestamp` : Allows senders to timestamp segments
- `Windows Scale` : Allows the host to 'scale up' its window size by a factor in situations where sending data to the sender from the receiver takes longer than sending to the receiver

## TCP Connection Establishment: Three-way Handshare

### Initiating a Connection

When a client host wants to **open a TCP connection** with a server host, it creates and sends a TCP segment with:

- the `SYN` flag set
- the sequence number set to a random initial value (does not start with 0, _this is randomized to prevent **TCP Sequence Prediction Attack**_)

### Responding to an Initial Connection Message

Upon reception of this segment (which is often called a `SYN` segment), the server host replies with a segment containing:

- the `SYN` flag set
- the sequence number set to a random number
- the `ACK` flag set
- the acknowledgment number set to the sequence number of the received `SYN` segment incremented by 1 mod 2^32 (because the `SYN` segment consumes one byte)

Note that when a TCP entity sends a segment with `x+1` as the acknowledgment number, it means that it has received all the segments up to and including the segments with the sequence number `x`, and that it’s expecting data having sequence number `x+1`. This segment is often called a `SYN+AC`K segment. The acknowledgment confirms to the client that the server has correctly received the `SYN` segment. The random sequence number of the `SYN+ACK` segment is used by the server host to verify that the client has received the segment.

### Acknowledging The Response

Upon reception of the `SYN+ACK` segment, the client host replies with a segment containing:

- the `ACK` flag set
- the acknowledgment number set to the sequence number of the received `SYN+ACK` segment incremented by 1
