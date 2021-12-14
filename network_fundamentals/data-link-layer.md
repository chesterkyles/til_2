# Data Link Layer

## Responsibilities of the Data Link Layer

The data link layer receives packets from the network layer and deals with **providing hop to hop communication** or communication between entities that are **directly connected by a physical link**.

In other words, it makes intelligible communication possible over a physical link that just transports 0s and 1s between two directly connected hosts.

## Types of Data Link Layers

The data link layer is the lowest layer of the reference model that we will discuss in detail. Data link layer protocols exchange **frames** that are transmitted through the physical layer. There are three main types of data link layers:

1. The _simplest_ data link layer type is one that has only **two communicating systems connected directly through the physical layer** also known as the **point-to-point data link layer**. This type of layer can either provide an unreliable service or a reliable service. The unreliable service is frequently used above physical layers (e.g., optical fiber, twisted pairs) that have a low bit error ratio, while reliability mechanisms are often used in wireless networks to recover locally from transmission errors.
2. The second type of data link layer is the one used in Local Area Networks (LAN) called **Broadcast multi-access**. Both end-systems and routers can be connected to a LAN.
   - An important difference between point-to-point data and Broadcast multi-access is that in a Broadcast multi-access, **each communicating device is identified by a unique data link layer address**. This address is usually embedded in the hardware of the device and different types of LANs use different types of data link layer addresses. However, since there is only one party at the “other end of the wire,” in point-to-point, there is no ambiguity what entity should receive a frame that is transmitted, thus there is no need for addressing.
   - A communicating device attached to a LAN can send a data link frame to any other communication device that is attached to the same LAN.
   - Most LANs also **support special broadcast and multicast data link layer addresses**. A frame sent to the broadcast address of the LAN is delivered to all communicating devices that are attached to the LAN. The multicast addresses are used to send a frame to one specific group.
3. The third type of data link layer is used in **Non-Broadcast Multi-Access (NBMA) networks**. These networks are used to interconnect devices like a LAN. All devices attached to an NBMA network are identified by a unique data link layer address.
   - The main difference between an NBMA network and a traditional LAN is that the NBMA service **only supports unicast and supports neither broadcast nor multicast**.
   - ATM, Frame Relay and X.25 are examples of NBMA.

## Limitations Imposed Upon The Data Link Layer

### By the Data Link Layer

The data link layer uses the service provided by the physical layer. Although there are many different implementations of the physical layer from a technological perspective, they all provide a service that enables the data link layer to send and receive bits between directly connected devices. Most data link layer technologies **impose limitations on the size of the frames**:

1. Some technologies only impose a maximum frame size.
2. Others enforce both minimum and maximum frame sizes.
3. Finally, some technologies only support a single frame size. In this case, the data link layer will usually include an adaptation sub-layer to allow the network layer to send and receive variable-length packets. This adaptation layer may include fragmentation and reassembly mechanisms.

### By the Physical Layer

The physical layer service facilitates the sending and receiving of bits, but it’s usually far from perfect:

- The physical layer **may change the value of a bit** being transmitted due to any reason, e.g., electromagnetic interferences.
- The Physical layer **may deliver more bits** to the receiver than the bits sent by the sender.
- The Physical layer **may deliver fewer bits** to the receiver than the bits sent by the sender.

## The Framing Problem

The data link layer must allow end systems to exchange frames containing packets despite all of these limitations.

On point-to-point links and Local Area Networks, the first problem to be solved is **how to encode a frame as a sequence of bits** so that the receiver can easily recover the received frame **despite the limitations of the physical layer**. This is the **framing problem**. It can be defined as: "_How does a sender encode frames so that the receiver can efficiently extract them from the stream of bits that it receives from the physical layer?_”

The following are some of the solutions:

1. **Idle Physical Layer** : A first solution to solve the framing problem is to **require the physical layer to remain idle for some time after the transmission of each frame**. These idle periods can be detected by the receiver and serve as a marker to indicate frame boundaries.
2. **Multi-symbol Encodings** : **All physical layer types are able to send and receive physical symbols that represent values 0 and 1**. Also, **several physical layer types are able to exchange other physical symbols as well**. Some technologies use these other special symbols as markers for the beginning or end of frames. For example, the Manchester encoding used in several physical layers can send four different symbols.
3. **Stuffing** : Unfortunately, multi-symbol encodings cannot be used by all physical layer implementations and a generic solution with which any physical layer that is able to transmit and receive only 0s and 1s works is required. This **generic solution is called stuffing** and two variants exist: bit stuffing, and character stuffing. To enable a receiver to easily delineate the frame boundaries, these two techniques **reserve special bit strings** as frame boundary markers and encode the frames such that these special bit strings do not appear inside the frames.

### Bit Stuffing

Bit stuffing is the insertion of non information bits into data. Bit stuffing reserves a special bit pattern, for example, the `01111110` bit string as the frame boundary marker. However, if the same bit pattern occurs in the data link layer payload, it must be modified before being sent, otherwise, the receiving data link layer entity will detect it as a start or end of frame. For example:

Original Frame | Transmitted Frame
-------------- | -----------------
0001001001001001001000011 | 01111110000100100100100100100001101111110
01111110 | 0111111001111101001111110

For example, consider the transmission of 0110111111111111111110010.

1. The sender will first send the 01111110 marker followed by 011011111.
2. After these five consecutive bits set to 11, it inserts a bit set to 00 followed by 11111.
3. A new 0 is inserted, followed by 11111.
4. A new 0 is inserted followed by the end of the frame 110010 and the 01111110 marker.

Read more about bit stuffing here: <https://www.tutorialspoint.com/what-is-bit-stuffing-in-computer-networks>

### Character Stuffing

This technique operates on frames that contain an integer number of characters of a fixed size, such as 8-bit characters. Some characters are used as markers to delineate the frame boundaries. Many character stuffing techniques use the DLE, STX and ETX characters of the ASCII character set. DLE STX is used to mark the beginning of a frame, and DLE ETX is used to mark the end of a frame.

> Software implementations prefer to process characters than bits, hence software-based data link layers usually use character stuffing.

For example, to transmit frame 1 2 3 DLE STX 4:

1. A sender will first send DLE STX as a marker
2. Followed by 1 2 3 DLE
3. Then, the sender transmits an additional DLE character
4. Followed by STX 4 and the DLE ETX marker
5. The final string is: DLE STX 1 2 3 DLE DLE STX 4 DLE ETX

Original Frame | Transmitted Frame
-------------- | -----------------
1 2 3 4 | DLE STX 1 2 3 4 DLE ETX
1 2 3 DLE STX 4 | DLE STX 1 2 3 DLE DLE STX 4 DLE ETX
DLE STX DLE ETX | DLE STX DLE DLE STX DLE DLE ETX DLE ETX

> DLE is the bit pattern 00010000, STX is 00000010 and ETX is 00000011.

Read more about character stuffing here: <https://www.tutorialspoint.com/what-is-byte-stuffing-in-computer-networks>

Disadvantages of Stuffing:

1. In character stuffing and in bit stuffing, the length of the transmitted frames is increased. The worst case redundant frame in case of bit stuffing is one that has a long sequence of all 1s, whereas in the case of character stuffing, it’s a frame consisting entirely of DLE characters.
2. When transmission errors occur, the receiver may incorrectly decode one or two frames (e.g., if the errors occur in the markers). However, it’ll be able to resynchronize itself with the next correctly received markers.
3. Bit stuffing can be easily implemented in hardware. However, implementing it in software is difficult given the higher overhead of bit manipulations in software.

## Error Detection

Besides framing, the data link layer also includes mechanisms to detect and sometimes even recover from transmission errors.

To allow a receiver to detect transmission errors:

1. A sender must add some redundant information (some `r` bits) as an error detection code to the frame sent. This error detection code is computed by the sender on the frame that it transmits.
2. When the receiver receives a frame with an error detection code, it recomputes it and verifies whether the received error detection code matches the computed error detection code.
3. If they match, the frame is considered to be valid.

To understand error detection codes, let us consider two devices that exchange bit strings containing N bits. To allow the receiver to detect a transmission error:

1. The sender converts each string of `N` bits into a string of `N+r` bits.
2. Usually, the `r` redundant bits are added at the beginning or the end of the transmitted bit string, but some techniques interleave redundant bits with the original bits.
3. An error detection code can be defined as a function that computes the `r` redundant bits corresponding to each string of `N` bits.

### Parity Bit

The simplest error detection code is the parity bit. In this case, the number of redundant bits is 1. There are two types of parity schemes:

1. **Even parity**: With the even parity scheme, the redundant bit is chosen so that an even number of bits are set to 1 in the transmitted bit string of `N+1` bits.
2. **Odd parity**: With the odd parity scheme, the redundant bit is chosen so that an odd number of bits are set to 1 in the transmitted bit string of `N+1` bits.

The receiver can easily recompute the parity of each received bit string and discard the strings with an invalid parity. The parity scheme is often used when 7-bit characters are exchanged. In this case, the eighth bit is often a parity bit.

The table below shows the parity bits that are computed for bit strings containing three bits.

3 bits string | Odd parity | Even parity
------------- | ---------- | -----------
000 | 1 | 0
001 | 0 | 1
010 | 0 | 1
100 | 0 | 1
111 | 0 | 1
110 | 1 | 0
101 | 1 | 0
011 | 1 | 0

### Error Correction Mechanisms

It is also possible to design a code that allows the receiver to **correct transmission errors**. The simplest error correction code is the **triple modular redundancy (TMR)**.

- To transmit a bit set to `1`, the sender transmits `111` and to transmit a bit set to `0`, the sender transmits `000`.
- When there are no transmission errors, the reciever can decode `111` as `1`.
- If transmission errors have affected a single bit, the receiver performs majority voting. This scheme allows the receiver to correct all transmission errors that affect a single bit.

Other more powerful error correction codes have been proposed and are used in some applications. The [**Hamming Code**](https://en.wikipedia.org/wiki/Hamming_code) is a clever combination of parity bits that provides error detection and correction capabilities.

In practice, data link layer protocols combine bit stuffing or character stuffing with a length indication in the frame header and a checksum. The checksum is computed by the sender and placed in the frame before applying bit/character stuffing.

## Ethernet

Ethernet was designed in the 1970s at the Palo Alto Research Center. The first prototype used a coaxial cable as the shared medium and 3 Mbps of bandwidth. Ethernet was improved during the late 1970s and in the 1980s, Digital Equipment, Intel and Xerox published the first official Ethernet specification.

This specification defines several important parameters for Ethernet networks:

1. The first decision was to **standardize the commercial Ethernet at 10 Mbps**.
2. The second decision was the **duration of the slot time**. In Ethernet, a long slot time enables networks to span a long distance but forces the host to use a larger minimum frame size. The compromise was a **slot time of 51.2 microseconds**, which corresponds to a minimum frame size of 64 bytes.
3.The third decision was the **frame format**. The experimental 3 Mbps Ethernet network built at Xerox used short frames containing 88 bit source and destination address fields. Up to 554554 bytes of payload using 88 bit addresses was suitable for an experimental network, but it was clearly too small for commercial deployments. Hence, they came up with 4848 bit source and destination address fields and up to 15001500 bytes of payload.

### MAC Addresses

The data link layer addresses used in Ethernet networks are often called **MAC addresses**:

- The first bit of the address indicates whether the address identifies a network adapter or a multicast group.
- The upper 24 bits are used to encode an Organization Unique Identifier (OUI). This OUI identifies a block of addresses that has been allocated by the secretariat who is responsible for the uniqueness of Ethernet addresses to a manufacturer. For instance, `00000C` belongs to **Cisco Systems Inc.**. Once a manufacturer has received an OUI, it can build and sell products with any of the ~16 million addresses in this block. A manufacturer may obtain more than one OUIs.

```sh
$ ifconfig

eth0      Link encap:Ethernet  HWaddr 02:42:ac:11:00:02  
          inet addr:172.17.0.2  Bcast:172.17.255.255  Mask:255.255.0.0
          UP BROADCAST RUNNING MULTICAST  MTU:1500  Metric:1
          RX packets:2 errors:0 dropped:0 overruns:0 frame:0
          TX packets:0 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:0 
          RX bytes:180 (180.0 B)  TX bytes:0 (0.0 B)

lo        Link encap:Local Loopback  
          inet addr:127.0.0.1  Mask:255.0.0.0
          UP LOOPBACK RUNNING  MTU:65536  Metric:1
          RX packets:0 errors:0 dropped:0 overruns:0 frame:0
          TX packets:0 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:1000 
          RX bytes:0 (0.0 B)  TX bytes:0 (0.0 B)
```

### Ethernet Frames

The original 10 Mbps Ethernet specification defined a simple frame format where each frame is composed of five fields.

0. The Ethernet frame starts with a **preamble** that’s used by the physical layer of the receiver to synchronise its clock with the sender’s clock.
1. The first field of the frame is the **destination address**. As this address is placed at the beginning of the frame, an Ethernet interface can quickly verify whether it’s the frame recipient and if not, cancel the processing of the arriving frame.
2. The second field is the **source address**. While the destination address can be either a unicast or a multicast/broadcast address, the source address must always be a unicast address.
3. The third field is a 16 bit integer that indicates which type of network layer packet is carried inside the frame. This field is often called the **Ether Type**. Frequently used EtherType values include: 0x0800 for IPv4, 0x86DD for IPv6, and 0x806 for the Address Resolution Protocol (ARP).
4. The fourth part of the Ethernet frame is the **payload**. The minimum length of the payload is 46 bytes to ensure a minimum frame size, including the header of 64 bytes. The Ethernet payload cannot be longer than 1500 bytes. This size was found reasonable when the first Ethernet specification was written. 1500 bytes was large enough without forcing the network adapters to contain overly large memories.
5. The last field of the Ethernet frame is a 32 bit **Cyclical Redundancy Check (CRC)**. This CRC is able to catch a much larger number of transmission errors than the Internet checksum used by IP, UDP and TCP.

The IEEE decided to **replace the Type field with a length field**. This Length field contains the **number of useful bytes in the frame payload**. The payload must still contain at least 46 bytes, but padding bytes are added by the sender and removed by the receiver.

Without the type field, however, it’s impossible for a receiving host to identify the type of network layer packet inside a received frame. To solve this new problem, IEEE developed a completely new sublayer called the **Logical Link Control**. Several protocols were defined in this sublayer. One of them provided a slightly different version of the Type field of the original Ethernet frame format. Another contained acknowledgments and retransmissions to provide a reliable service.

### Ethernet physical layer

The table below lists the main Ethernet standards. A more detailed list may be found [here](https://en.wikipedia.org/wiki/Ethernet_physical_layer).

Standard	| Comments
-------- | --------
10Base2 | Thick coaxial cable, 500m
10Base5 | Thin coaxial cable, 185m
10BaseT | Two pairs of category 3+ UTP
10Base-F | 10 Mb/s over optical fiber
100Base-Tx | Category 5 UTP or STP, 100 m maximum
1000Base-CX | Two multimode optical fiber, 2 km maximum
100Base-FX | Two multimode or single mode optical fibers with lasers
1000Base-SX | Two pairs shielded twisted pair, 25m maximum
40-100 Gbps | Optical fiber but also Category 6 UTP

### Ethernet Switches

Increasing the physical layer bandwidth as in Fast Ethernet was only one of the solutions to improve the performance of Ethernet LANs. 

A second solution was to replace the hubs with more intelligent devices. As Ethernet hubs operate in the physical layer, they can only regenerate the electrical signal to extend the geographical reach of the network. From a performance perspective, it would be more interesting to have devices that operate in the data link layer and can analyze the destination address of each frame and forward the frames selectively on the link that leads to the destination. This would allow two hosts to communicate on one pair of interfaces while other pairs of interfaces can be simultaneously used for other communication, thereby improving communication efficiency. Such devices are usually called Ethernet switches. **An Ethernet switch is a relay that operates in the data link layer**.

#### MAC Address Tables

An Ethernet switch understands the format of the Ethernet frames and can _selectively_ forward frames over each interface. For this, each Ethernet switch **maintains a MAC address table**. This table contains, for each MAC address known by the switch, the identifier of the switch’s port over which a frame sent towards this address must be forwarded to reach its destination.

#### Retaining Plug and Play with Switches

One of the selling points of Ethernet networks is that thanks to the utilization of 48 bit MAC addresses, an Ethernet LAN is plug and play at the data link layer. When two hosts are attached to the same Ethernet segment or hub, they can immediately exchange Ethernet frames without requiring any configuration.

It is important to retain this plug and play capability for Ethernet switches as well. This implies that **Ethernet switches must be able to build their MAC address table automatically without requiring any manual configuration**. This automatic configuration is performed by the MAC address learning algorithm that runs on each Ethernet switch.

1. This algorithm extracts the source address of the received frames and remembers the port over which a frame from each source Ethernet address has been received.
2. This information is inserted into the MAC address table that the switch uses to forward frames.
3. This allows the switch to automatically learn the ports that it can use to reach each destination address, provided that this host has previously sent at least one frame. This is not a problem since most upper-layer protocols use acknowledgments at some layer and thus even an Ethernet printer sends Ethernet frames as well.

The pseudocode below details how an Ethernet switch forwards Ethernet frames:

```sh
# Arrival of frame F on port P
# Table : MAC address table dictionary : addr->port
# Ports : list of all ports on the switch
src=F.SourceAddress
dst=F.DestinationAddress
Table[src]=P  # src heard on port P
if isUnicast(dst):
    if dst in Table:
        ForwardFrame(F,Table[dst])
    else:
        for o in Ports:
          if o!= P: ForwardFrame(F,o)
else:
    # broadcast destination
    for o in Ports:
        if o!=P:  ForwardFrame(F,o)
```

#### Spanning Tree Protocol

The **spanning tree protocol** enables switches to exchange control messages by means of which a root switch is first elected in the topology. Then, all switches designate one of their ports that reaches the root switch with the minimum number of hops to be part of the spanning tree. All other ports that lead to the root switch, and hence create a loop are disabled as far as frame forwarding is concerned. Eventually, the frames are only forwarded on ports that are part of the spanning tree. The ports that are not part of the spanning tree continue to send and receive control frames. This helps to recover from switch or link failures.
