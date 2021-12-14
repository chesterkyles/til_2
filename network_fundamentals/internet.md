# Internet and the OSI Model

## Terminologies

- **Internet** - global network of computer networks
- **Network** - group or sstem of interconneted people or items
- **Computer network** - a network where computers are conntected to each other with cable or wireless radio

Purpose of Computer Networks:

- Communication using computers
- Sharing of resources

## Layered Architectures

Layered architectures give us modularity by allowing us to discuss **specific**, **well-defined parts of larger systems**. This makes **changing implementation-level details** and **identifying bugs easier**.

**Each layer provides some services to the layer above it**. Furthermore, the layer above is **not concerned with the details of _how_ the layer below performs its services**. This is called **ABSTRACTION**. So in this way, the layers communicate with each other in a _vertical_ fashion.

Note also that each layer at the sending end has a parallel in the receivin end. Hence, layers also communication with their parallels in a _horizontal_ fashion.

### Vertical Layer

Compuiter networks are conceptually divided into layers that each serves the layer above and below it.

- For example, the top layer in tmost layered models is called the **application layer**. End-user applications live in the application layer, which includes the web and email and are almost always implemented in software. The application layer is also where an outgoing message starts its journey.
- The application needs an underlying service that can get application messages delivered from source to destination and bring back replies which is what the layer(s) after do(es).

Since the underlying layer collects messages from the upper layer for delivery to the destination and hands over messages destined for the upper layer, it **serves the application layer**. Furthermore, the application layer **abstracts**, and hence is not concerned with any implementation details of the layers below.

### Horizontal Layer

For example, applications in the **application layer** send and receive data from the network. The application layer on one end system has a parallel on another end system, i.e., a chat app on one end system could be communicating with a chat app on another. **These application in the application layer are seemingly coomunicating with each other directly or horizontally**.

## Encapsulation and Decapsulation

Each layer adds its own header to the message coming from above and the receiving entity on the other end removes it. The information in each header is useful for transmitting the message to the layer above. Adding the header is called **encapsulation** and removing it is **decapsulation**.

## Open Systems Interconnection (OSI) Model

There are several models along which computer networks are organized. The two most common ones are the **Open Systems Interconnection (OSI)** model and the **Transmission Control Protocal/Internet Protocal (TCP/IP)** model.

The OSI model **provides a standard** for different computer systems to be able to communicate with each other. The seven layers of the OSI Model are (with mnemonics, _Please Do Not Throw Sausage Pizza Away_):

```md
- Application   - Away
- Presentation  - Pizza
- Session       - Sausage
- Transport     - Throw
- Network       - Not
- Data Link     - Do
- Physical      - Please
```

The main purpose of this "network stack" is to **understand _how_ the components of these protocols fit into the stack and work with each other**.

### Some of the Responsibilities of Each Layer

#### Application Layer

- These applications or protocols are almost always implemented in software.
- End-users interact with the application layer.
- The application layer is where most end-user applications such as web browsing and email live.
- The application layer is where an outgoing message starts its journey so it provides data for the layer below.

#### Presentation Layer

- Presents data in a way that can be easily understood and displayed by the application layer.
  - Encoding is an example of such presentation. The underlying layers might use a different character encoding compared to the one used by the application layer. The presentation layer is responsible for the translation.
- Encryption (changing the data so that it is only readable by the parties it was intended for) is also usually done at this layer.
- Abstracts: the presentation layer assumes that a user session is being maintained by the lower layers and transforms content presentation to suit the application.
- End-to-end Compression: The oresentation layer might also implement end to end compression to reduce the traffic in network.

#### Session Layer

- The session's layer responsibility is to take the services of the transport layer and build a service on top of it that **manages user sessions**.
  - The transport layer is responsible for transporting session layer messages across the network to the destination. The session layer must manage the mapping of messages delivered by the transport layer to the sessions.
- A session is an exchange of information between local applications and remote services on other end systems.
  - For example, one session spans a customer's interaction with an e-commerce site whereby they search, browse and select products, then make the payment and logout.
- Abstracts: the session layer assumes that connections establishmend and packet transportatoin is handled by the layers below it.

#### Transport Layer

- The transport layer also has protocols implemented largely in software.
- SInce the application, presentation and session layers may be handing off large chunks of data, the transport layer segments it into smaller chunks.
  - These chunks are called **datagrams or segments** depending on the protocol used.
- Sometimes, some additional information is required to transmit the segment/datagram reliably. The transport layer adds this information to the segment/datagram.
  - An example of this would be the **checksum**, which helps ensure that the message is correctly delivered to the destination, i.e. that it's not corrupted and changed to something else on the way.
  - When additional information is added to the **start** of a segment/datagram, it is called as **header**.
  - When additional information is appended to the **end**, it is called a **trailer**.

#### Network Layer

- Network layer messages are termed as **packets**.
- They facilitate the transportation of packets from on end system to another and help to determine the best routes that messages should take from one end system to another.
- **Routing protocols** are applications that run on the network layer and exchange messages with each other to develop information that helps them route transport layer messages.
- **Load balancing**: There are many links (copper wire, optical fiber, wireless) in a given network and one objective of the network layer is to keep them all roughly equally utilized. Otherwise, if some links are under-utilized, there will be concerns about the economic sense of deploying and managing them.

#### Data Link Layer

- Allows directly connected hosts to communicate. Sometimes these hosts are the only two things on a physical medium. In that case, the challenges that this layer addresses include **flow control** and **error detection/correction**.
- Encapsulates packets for transmission across a single link.
- Resolves transmission conflicts, i.e. when two end systems send a message at the same time across one singular link.
- Handles addressing: If the data link is a broadcast medium, addressing is another data link layer problem.
- Multiplexing and demultiplexing
  - Multiple data links can be multiplexed into something that appears like one, to integrate their bandwidths.
  - Likewise, sometimes, we disaggregate a single data link into virtual data links which appear like separate network interfaces.

#### Physical Layer

- Consists largely of hardware.
- Provides a solid electrical and mechanical medium to transmit the data.
- Transmits bits. Not logical packets, datagrams, or segments.
- Also has to deal with mechanical specifications about the makeup of the cables and the design of the connectors.

## TCP/IP Model

### Introduction

- The TCP/IP Model, also known as the Internet protocol suite, was developed in 1989 and was funded by DARPA.
- Its technical specifications are detailed in [RFC 1122](https://tools.ietf.org/html/rfc1122).
- This model is primarily based upon the most protocols of the Internet, namely the **Internet Protocol (IP)** and the **Transmission Control Protocol** (TCP).
- The protocols in each layer are clearly defined, unline in the OSI model. They are the following:

```txt
Application
Transport
Network
Data Link
Physical
```

### TCP/IP vs OSI

TCP/IP | OSI
-------| ----
Is used practically | The OSI model is conceptual and is not practically used for communication
Consists of five layers | Consists of seven layers

#### Notes

- OSI model is a theoretical model and works very well for teaching purposes, but it is far too complex for anyone to implement.
- TCP/IP, on the other hand, wasn't really a model. People just implemented it and got it to work. Then, people reverse-engineered a reference model out of it for theoretical and pedagogical purposes.
- The TCP/IP protocol suite is heavily influenced by the following design choice, also known as the end-to-end argument.
- Furthermore, the core was made packet-switched, which means that packet are router per-hop, so they can circumvent failures because the requirement was for resilience.
