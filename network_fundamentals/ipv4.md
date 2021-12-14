# IP version 4 (IPv4)

The design of IPv4 was based on the following assumptions:

- IP should provide an _unreliable connectionless service_
- IP operates with the _datagram transmission mode_
- IP hosts must have _fixed size 32-bit addresses_
- IP must be _compatible with a variety of data link layers_
- IP hosts should be able to _exchange variable-length packets_

## IP Addresses

The addresses are an important part of any network layer protocol. IPv4 addresses are written as 32 bit numbers in **dotted-decimal format**, such as a sequence of four integers separated by dots. Dotted decimal is a format imposed upon the 32-bit numbers for relatively easier human readability. For example:

- 1.2.3.4 corresponds to 00000001000000100000001100000100
- 127.0.0.1 corresponds to 01111111000000000000000000000001
- 255.255.255.255 corresponds to 11111111111111111111111111111111

## Multihoming

An IPv4 address is used to identify an interface on a router or an interface on a host.

A router has thus as many IPv4 addresses as the number of interfaces that it has in the data link layer. Most hosts have a single data link layer interface and thus have a single IPv4 address. However, with the growth of wireless more and more hosts have several data link layer interfaces (for example, an Ethernet interface and a WiFi interface). These hosts are said to be **multihomed**. A multihomed host with two interfaces has thus two IPv4 addresses.

## Address Assignment

Appropriate network layer address allocation is key to the efficiency and scalability of the Internet.

A **naive allocation scheme** would be to provide an IPv4 address to each host when the host is attached to the Internet on a first come, first served basis.  Unfortunately, this would force all routers to maintain a specific route towards all approximately 1 Billion hosts on the Internet, which is not scalable. Hence, it’s important to minimize the number of routes that are stored on each router.

### Subnetting

One solution is that routers should only maintain routes towards **blocks of addresses** and not towards individual hosts. For this, blocks of IP addresses are assigned to ISPs. The ISPs assign sub blocks of the assigned address space in a hierarchical manner. **These sub blocks of IP addresses are called subnets**.

A typical subnet groups all the hosts that are part of the same enterprise. An enterprise network is usually composed of several LANs interconnected by routers. A small block of addresses from the Enterprise’s block is usually assigned to each LAN. An IPv4 address is composed of two parts:

- A **subnetwork identifier** composed of the high order bits of the address.
- And a **host identifier** encoded in the lower order bits of the address.

### Address Class

When a router needs to forward a packet, it must know the subnet of the destination address to be able to consult its routing table to forward the packet. RFC 791 proposed to use the high-order bits of the address to encode the length of the subnet identifier. This led to the definition of three classes of addresses.

 Class | High-order bits | Length of subnet id | Number of networks | Address per network
 ----- | --------------- | ------------------- | ------------------ | -------------------
 Class A | `0` | `8` bits | `128 (2^7)` | `16,2777,216 (2^24)`
 Class B | `10` | `16` bits | `16,384 (2^14)` | `16,2777,216 (2^16)`
 Class C | `110` | `24` bits | `2,097,1521 (2^21)` | `256 (2^8)`

In this **classful address scheme**, the range range of the IP addresses in each class are as follows:

- **Class A**: `0.0.0.0` to `127.255.255.255`
- **Class B**: `128.0.0.0` to `191.255.255.255`
- **Class C**: `192.0.0.0` to `223.255.255.255`
- **Class D**: `224.0.0.0` to `239.255.255.255`
- **Class E**: `240.0.0.0` to `255.255.255.255`

**Class D** IP addresses are used for multicast, whereas **class E** IP addresses are reserved and can’t be used on the Internet. So classes A, B, and C are the ones used for regular purposes.

### Subnet Masks

Every network that falls into one of these classes has a fixed number of bits in the network part to identify the network itself. The subnet mask ‘masks’ the network part of the IP address and leaves the host part open. So a subnet mask of a class C address could be `203.128.22.0`, where the first 3 octets represent the subnet mask and the last octet can be used to identify hosts within this network. For instance, `203.128.22.10` can be one machine on this network.

### Network Address

The network address is just the address with all the host bits set to `0`. So `203.128.22.0` is actually a network address. It is technically not a ‘functional’ address, it’s just used for forwarding table entries.

### Broadcast Address

The broadcast address of any network is the one where the host bits are all set to `1`. So the broadcast address in our example subnet mask is `203.128.22.255`. It can be used to broadcast a packet to all devices on a network.

### Default Subnet Masks

Each class has a default mask as follows where the network ID portion has all 11s and the host ID portion has all `0s`.

Class   | Default Subnet Mask
------- | -------------------
Class A | `255.0.0.0`
Class B | `255.255.0.0`
Class C | `255.255.255.0`

However, these three classes of addresses were not flexible enough. A **class A** subnet was **too large** for most organizations and a **class C** subnet was **too small**.

### Variable-Length Subnets

Flexibility was added by the introduction of variable-length subnets in [RFC 1519](https://tools.ietf.org/html/rfc1519). With variable-length subnets, the subnet identifier can be any size, from 1 to 31 bits. Variable-length subnets allow the network operators to use a subnet that better matches the number of expected hosts that will use the subnet. A subnet identifier or IPv4 prefix is usually represented as **A.B.C.D/p**, where **A.B.C.D is the network address**. It’s obtained by concatenating the subnet identifier with a host identifier containing only 0, and **p is the length of the subnet identifier in bits**.

The table below provides examples of variable-length IP subnets.

Subnet       | # Addresses | Lowest Address | Highest Address
------------ | ------------------- | -------------- | ---------------
`10.0.0.0/30`| `4`   | `10.0.0.0`  | `10.0.0.3`
`192.0.2.0/4`| `256` | `192.0.2.0` | `192.0.2.255`

## Address Allocation

### Allocating Blocks of Addresses to Organizations

A second issue concerning the addresses of the network layer is how to allocate blocks of addresses to organizations.

- The first allocation scheme was to allocate class address blocks on a first come, first served basis.
- Large organizations such as **IBM**, **BBN**, as well as **Stanford** or **MIT** were able to obtain one class A address block each.
- However, **most organizations requested class B address blocks** consisting of `65,536` addresses, which was suitable for their size. Unfortunately, there were only 16,384 different class B address blocks. **This address space was being consumed quickly**. Since a disproportionate number of class B address blocks were being used, the number of entries for class B blocks increased. So the routing tables maintained by the routers were also growing quickly, and some routers had difficulties maintaining all these routes in their limited memory. Hence, **the purpose of address space classes was being defeated**.

### Classless Interdomain Routing

Faced with these two problems, the Internet Engineering Task Force decided to develop the **Classless Interdomain Routing (CIDR)** architecture [RFC 1518](http://tools.ietf.org/html/rfc1518.html). This architecture allows IP routing to scale better than class-based architecture. CIDR contains three important modifications over class-based architecture:

1. IP address classes are deprecated. All IP equipment must use and support **variable-length subnets**.
2. IP address blocks are no longer allocated on a first come, first served basis. Instead, CIDR introduces a **hierarchical address allocation scheme**. The main draw-back of the first come, first served address block allocation scheme was that neighboring address blocks were allocated to very different organizations and conversely, very different address blocks were allocated to similar organizations.
3. IP routers must use **longest-prefix match** when they look up a destination address in their forwarding table.

With CIDR, address blocks are allocated by **Regional IP Registries** (RIR) in an aggregatable manner. A RIR is responsible for a large block of addresses and a region. For example, RIPE is the RIR that is responsible for Europe. A RIR allocates smaller address blocks from its large block to **Internet Service Providers (ISPs)**. ISPs then allocate smaller address blocks to their customers.

The main advantage of this hierarchical address block allocation scheme is that it allows the routers to maintain fewer routes.

## Classless Interdomain Routing vs Variable-length Subnets

Variable-length subnets steal bits from the host portion of the IP address. Classless interdomain routing also allows aggregation of smaller subnets into larger ones by making less specific subnet masks. For example, 190.10.1.0/24, 190.10.2.0/24, 190.10.3.0/24 and 190.10.4.0/24 can be summarized into 190.10.0.0/21. This reduces the number of entries that a router advertises, thereby controlling the size of the routing tables in the core of the Internet.

Furthermore, in Variable-length subnets the default subnet mask of the classes is strictly extended, whereas in CIDR, classes do not exist at all. So the ‘default’ length can be extended or reduced. Therefore, variable-length subnets are used if someone needs fewer addresses generally, whereas CIDR is for reducing routing table entries.

## Special IPv4 Addresses

Most unicast IPv4 addresses can appear as source and destination addresses in packets on the global Internet. It’s worth noting though, that some blocks of IPv4 addresses have a special usage, as described in [RFC 5735](http://tools.ietf.org/html/rfc5735.html). These include:

- **0.0.0.0/8**: reserved for self-identification.
- **127.0.0.0/8**: reserved for loopback addresses. Each IPV4 host has a loopback interface (that’s not attached to a data link layer). By convention, IPv4 address 127.0.0.1 is assigned to this interface as we saw in the previous chapter. This allows processes running on a host to use TCP/IP to contact other processes running on the same host. This is very useful for testing purposes. Furthermore, loopback interfaces can not be down. If the device is up, so are its loopback interfaces. You can configure as many loopback interfaces as you want. In such a case, the loopback interfaces can be assigned different IP addresses. Anyway, a loopback interface address is used as a router identifier when configuring some of the routing protocols. We want the routing process to keep running even if some of the physical interfaces go down. The loopback interface(s) provide the desired stability.
- **10.0.0.0/8**, **172.16.0.0/12** and **192.168.0.0/16** are reserved for private networks that are not directly attached to the Internet. These addresses are often called private addresses.
- **169.254.0.0/16** is used for link-local addresses. Some hosts use an address in this block when they’re connected to a network that does not allocate addresses as expected.

## IPv4 Packets

The IPv4 packet format was defined in [RFC 791](http://tools.ietf.org/html/rfc791.html). Apart from a few clarifications and some backward compatibility changes, the IPv4 packet format did not change significantly since the publication of RFC 791. All IPv4 packets use a 20-byte header. Some IPv4 packets contain an optional header extension.

### Packet Header

The main fields of the IPv4 header are:

- A 4 bit **version** that indicates the version of IP used to build the header. Using a version field in the header allows the network layer protocol to evolve.
- A 4 bit **IP Header Length (IHL)** that indicates the length of the IP header in 32-bit words. This field allows IPv4 to use options if required, but as it is encoded as a 4 bits field, the IPv4 header cannot be longer than 64 bytes.
- An 8 bit **DS field** that is used for Quality of Service.
A 16 bit **length field** that indicates the total length of the entire IPv4 packet (header and payload) in bytes. This implies that an IPv4 packet cannot be longer than 65535 bytes.
- **Identification**. Every packet has an identification number which is useful when reassembling and fragmenting a packet.
- **Flags**. There are three flags in IP headers:
  - Don’t Fragment
  - More Fragments
  - Reserved (must be zero)
- **Fragment Offset**. This is useful when reassembling a packet from its fragments.
- **Time to Live**. This number is decremented at each hop. When it becomes 0, the packet is considered to have been in the network for too long and is dropped.
- An 8 bits **Protocol field** that indicates the transport layer protocol that must process the packet’s payload at the destination. Common values for this field are 6 for TCP and 17 for UDP.
- A 16 bit **checksum** that protects only the IPv4 header against transmission errors.
- A 32 bit **source address field** that contains the IPv4 address of the source host.
- A 32 bit **destination address field** that contains the IPv4 address of the destination host.
- **Options**. This field is not used very often. It’s often used to test out experimental features.
- **IP Data** or the payload. This payload is not part of the checksum.

The other fields of the IPv4 header are used for very specific purposes.

### Handling Forwarding Loops with TTL

The first is the **8 bit Time To Live (TTL) field**. This field is used by IPv4 to avoid the risk of having an IPv4 packet caught in an infinite loop due to a transient or permanent error in routing tables.

The TTL field of the IPv4 header ensures that **even if there are forwarding loops in the network, packets will not loop forever**.

Hosts send their IPv4 packets with a positive TTL (usually 6464 or more). When a router receives an IPv4 packet, it first **decrements the TTL by one**. **If the TTL becomes 0, the packet is discarded** and a message is sent back to the packet’s source.

### Handling Data Link Layer Heterogeneity

A second problem for IPv4 is the heterogeneity of the data link layer. IPv4 is used above many very different data link layers. Each of which has its own characteristics. For example, each data link layer is characterized by a **maximum frame size** or **Maximum Transmission Unit (MTU)**. The MTU of an interface is the largest IPv4 packet (including header) that it can send. The table below provides some common MTU sizes.

Data link layer | MTU
--------------- | ---
Ethernet | 1500 bytes
IEEE 802.11 WiFi | 2304 bytes
Token Ring (802.15.4) | 4464 bytes
FDDI | 4352 bytes

## IPv4 Fragmentation and Reassembly

Although IPv4 packets can be as big as 64kB, few data link layer technologies can send a 64 KB IPv4 packet inside a frame.

Furthermore, if the host on the FDDI network abides by its own data link layer’s maximum packet size of 4478 bytes, the resulting data link layer frame would violate the maximum frame size of the Ethernet between routers R1 and R2. Hence, a host may end up sending a packet that is too large for a data link layer technology used by (an) intermediate router(s).

To solve these problems, IPv4 includes a **packet fragmentation and reassembly mechanism** in both hosts and intermediate routers. In IPv4, fragmentation is completely performed in the IP layer and a large IPv4 packet is fragmented into two or more IPV4 packets (called fragments).

### Fragmentation

The IPv4 fragmentation mechanism relies on four fields of the IPv4 header:

- Length
- Identification
- The flags
  - More fragments
  - Don’t Fragment (DF). When this flag is set, it indicates that the packet cannot be fragmented
- Fragment Offset.

The basic operation of IPv4 fragmentation is as follows:

- A large packet is fragmented into two or more fragments where the size of all fragments, except the last one, is equal to the Maximum Transmission Unit of the link used to forward the packet.
- The Length field in each fragment indicates the length of the payload and the header of the fragment.
- Each IPv4 packet contains a **16 bit Identification field**. When a packet is fragmented, the Identification of the large packet is copied in all fragments to allow the destination to reassemble the received fragments together.
- In each fragment, the **Fragment Offset** indicates, in units of 8 bytes, the position of the payload of the fragment in the payload of the original packet.
- When the **Don’t Fragment (DF) flag** is set, it indicates that the **packet cannot be fragmented**.
- Finally, the **More fragments flag** is set to indicate that more fragments are coming. This means that it will be set in all fragments except for the last one

### Reassembly

The fragments of an IPv4 packet **may arrive at the destination in any order** since each fragment is forwarded independently in the network and may follow different paths. Furthermore, **some fragments may be lost and never reach the destination**.

The **reassembly algorithm** used by the destination host is roughly as follows:

1. The destination can verify whether a received IPv4 packet is a fragment or not by checking the value of the **More fragments flag** and the **Fragment Offset**. If the Fragment Offset is set to 0 and the More fragments flag is reset, the received packet has not been fragmented. Otherwise, the packet has been fragmented and must be reassembled.
2. The reassembly algorithm relies on the Identification field of the received fragments to associate a fragment with the corresponding packet being reassembled
3. Furthermore, the Fragment Offset field indicates the position of the fragment payload in the original unfragmented packet.
4. Finally, the packet with the More fragments flag reset allows the destination to determine the total length of the original unfragmented packet.

### Handling Loss and Duplicates

Note that the reassembly algorithm must **deal with the unreliability of the IP network**: fragments may be duplicated or may never reach the destination. The destination can easily **detect fragment duplication with the Fragment Offset**.

To **deal with fragment losses, the reassembly algorithm must bind the time during which the fragments of a packet are stored** in its buffer while the packet is being reassembled. This can be implemented by starting a timer when the first fragment of a packet is received. If the packet has not been reassembled upon expiration of the timer, all fragments are discarded and the packet is considered to be lost.

## The Life of an IPv4 Packet

The simplest case is when a host needs to send a transport layer segment in an IPv4 packet. In order to do so, it performs two operations.

1. It must decide on which interface the packet will be sent.
2. It must create the corresponding IP packet(s).

An IPv4 host with `n` data link layer interfaces manage `n+1` IPv4 addresses:

- The `127.0.0.1/32` IPv4 address assigned by convention to its loopback address.
- One `A.B.C.D/p` IPv4 address assigned to each of its `n` data link layer interfaces.
- The host maintains a forwarding table that contains one entry for its loopback address and one entry for each subnet identifier assigned to its interfaces.
- Furthermore, the host usually uses one of its interfaces as the default interface when sending packets that are not addressed to a directly connected destination. This is represented by the default route: 0.0.0.0/0 that is associated with one interface.

### Sending a Packet

- When a transport protocol running on the host requests the transmission of a segment, it usually provides the IPv4 destination address to the IPv4 layer in addition to the segment.
- The IPv4 implementation first performs a longest prefix match with the destination address in its forwarding table. The lookup returns the identification of the interface that must be used to send the packet.
- The host can then create the IPv4 packet that contains the segment! The source IPv4 address of the packet is the IPv4 address of the host on the interface returned by the longest prefix match.
- The Protocol field of the packet is set to the identification of the local transport protocol which created the segment.
- The TTL field of the packet is set to the default TTL used by the host.
- The host must now choose the packet’s Identification. This Identification is important if the packet becomes fragmented in the network, as it ensures that the destination is able to reassemble the received fragments.
- Finally, the packet’s checksum is computed before transmission.

### Receiving a Packet

When a host receives an IPv4 packet destined to itself, there are several operations that it must perform.

- First, it must check the packet’s checksum. If the checksum is incorrect, the packet is discarded.
- Then, it must check whether the packet has been fragmented. If yes, the packet is passed to the reassembly algorithm. Otherwise, the packet must be passed to the upper layer. This is done by looking at the Protocol field (6 for TCP and 17 for UDP).
- If the host doesn’t implement the transport layer protocol corresponding to the received Protocol field, it sends a **Protocol unreachable ICMP message** to the sending host.

### If ICMP is Received

If the received packet contains an ICMP message (with the protocol field set to 1), the processing is more complex

- An **Echo-request ICMP message** triggers the transmission of an ICMP Echo-reply message.
- The other types of ICMP messages, except for ICMP Echo Response, indicate an error that was caused by a previously transmitted packet. These ICMP messages are usually forwarded to the transport protocol that sent the erroneous packet. This can be done by inspecting the contents of the ICMP message that includes the header and the first 64 bits of the erroneous packet.
- If the IP packet did not contain options, which is the case for most IPv4 packets, the transport protocol can find in the first 32 bits of the transport header the source and destination ports to determine the affected transport flow. This is important for Path MTU discovery for example.

### How Router Handle Packets

When a router receives an IPv4 packet, it must:

- First check the packet’s checksum. If the checksum is invalid, it’s discarded.
- Otherwise, the router must check whether the destination address is one of the IPv4 addresses assigned to the router. If so, the router must behave as a host and process the packet as described above. Although routers mainly forward IPv4 packets, they sometimes need to be accessed as hosts by network operators or network management software.
- If the packet is not addressed to the router, it must be forwarded on an outgoing interface according to the router’s forwarding table.
- The router first decrements the packet’s TTL.
  - If the TTL reaches 0, a **TTL Exceeded ICMP message** is sent back to the source.
  - As the packet header has been modified, the checksum must be recomputed. Fortunately, as IPv4 uses an arithmetic checksum, a router can incrementally update the packet’s checksum.
- Then, the router performs a longest prefix match for the packet’s destination address in its forwarding table.
  - If no match is found, the router must return a **Destination unreachable ICMP message** to the source.
  - Otherwise, the lookup returns the interface over which the packet must be forwarded.
- Before forwarding the packet over this interface, the router must first compare the length of the packet with the MTU of the outgoing interface.
  - If the packet is smaller than the MTU, it is forwarded.
  - Otherwise, a **Fragmentation needed ICMP message** is sent if the DF flag was sent or the packet is fragmented if the DF was not set.

## IPv6

IPv4 was initially designed for a research network that would interconnect some research labs and universities. For this purpose, 32 bit addresses, or approximately 4.3 billion addresses seemed sufficient. Also, 32 bits was an incredibly convenient address size for software-based routers.

However, the popularity of the Internet, i.e., the number of smartphones and Internet of Things devices, was not anticipated. Nonetheless, we are running out of addresses. Hence, **IPv6 was designed to tackle these limitations of IPv4**.

### Pros

- **Simplified Header**: All IPv4 options are moved to the end of the IPv6 header. IPv6 header is twice as large as IPv4 headers but only because IPv6 addresses are four times longer.
- **Larger Address Space**:  IPv6 addresses face 4 times as many bits as IPv4. So all IPv6 addresses are 128 bits wide.

### Cons

- IPv6 is a complete redesign over IPv4 and hence is not backward compatible. This means that devices configured over IPv4 can NOT access websites on servers configured with IPv6!
- Upgrading to IPv6 enabled hardware is an expensive shift for ISPs and is not directly translatable in terms of profit. This is part of the reason why the world has not shifted entirely to IPv6.

### Address Format and Types

The experience of IPv4 revealed that the scalability of a network layer protocol **heavily depends on its addressing architecture**. The designers of IPv6 therefore spent a lot of effort defining its addressing architecture [RFC 3513](https://datatracker.ietf.org/doc/html/rfc3513). IPv6 supports unicast, multicast and anycast addresses.

### Unicast Addresses

As with IPv4, an IPv6 unicast address is used to identify one data link layer interface on a host. If a host has several data link layer interfaces (such as an Ethernet interface and a WiFi interface), then it needs several IPv6 addresses. An IPv6 unicast address is composed of three parts:

1. A **global routing prefix** that is assigned to the Internet Service Provider that owns this block of addresses
2. A **subnet identifier** that identifies a customer of the ISP
3. An **interface identifier** that identifies a particular interface on an end-system

In practice, there are several types of IPv6 unicast address such as provider-independent (PI) addresses, unique local unicast (ULA) addresses, link-local unicast addresses.

Most of the [IPv6 unicast addresses](http://www.iana.org/assignments/ipv6-address-space/ipv6-address-space.xhtml) are allocated in blocks under the responsibility of [IANA](http://www.iana.org). The current IPv6 allocations are part of the 2000::/3 address block.

For the companies that want to use IPv6 without being connected to the IPv6 Internet, [RFC 4193](http://tools.ietf.org/html/rfc4193.html) defines the Unique Local Unicast (ULA) addresses (FC00::/7). These ULA addresses play a similar role as the private IPv4 addresses defined in RFC 1918. However, the size of the FC00::/7 address block allows ULA to be much more flexible than private IPv4 addresses.

The last type of unicast IPv6 addresses is the Link-Local Unicast addresses. These addresses are part of the FE80::/10 address block and are defined in [RFC 4291](http://tools.ietf.org/html/rfc4291.html).

### Anycast Addresses

RFC 4291 defines a special type of IPv6 anycast address. On a subnetwork having prefix `p/n`, the IPv6 address whose 128-n low-order bits are set to `0` is the anycast address that corresponds to all routers inside this subnet-work. This anycast address can be used by hosts to quickly send a packet to any of the routers inside their own subnetwork.

### Mulitcast Addresses

Finally, RFC 4291 defines the structure of the IPv6 multicast addresses. The lower order 112 bits of an IPv6 multicast address are the group’s identifier. The higher-order bits are used as a marker to distinguish multicast addresses from unicast addresses.