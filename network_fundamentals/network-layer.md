# Network Layer

## Main Objectives and Key Responsibilities

The main objective of the network layer is to allow end systems to exchange information through intermediate systems called **routers**. The unit of information in the network layer is called a **packet**.

### Limitations of the Underlying Data Link Layer

Messages at the data link layer are called **frames**. There are more than a dozen different types of data link layers.

1. Every data link layer technology has a limit on maximum frame size.
2. Most of them use a different maximum frame size.
3. Furthermore, each interface on an end system in the data link layer has a link layer address. This means the link layer has to have an addressing system of its own.

The network layer must cope with this heterogeneity of the data link layer.

### Principles of the Network Layer

The network layer relies on the following principles:

1. Each network layer entity is identified by a **network layer address**. This address is independent of the data link layer addresses that the entity may use.
2. The service provided by the network layer **does not depend** on the service or the internal organization of the **underlying data link layers**. This independence ensures:
   - **Adaptability**. The network layer can be used by hosts attached to different types of data link layers.
   - **Independent Evolution**. The data link layers and the network layer evolve independently from each other.
   - **Forward Compatibility**. The network layer can be easily adapted to new data link layers when a new type is invented.
3. The network layer is conceptually divided into **two planes**:
   1. The **data plane**. The data plane contains the protocols and mechanisms that allows _hosts and routers to exchange packets carrying user data_.
   2. The **control plane**. The control plane contains the protocols and mechanisms that _enables routers to efficiently learn how to forward packets towards their final destination_.

## Network Layer Services

There are two types of services that can be provided by the network layer:

- An unreliable connectionless service. This kind of service does not ensure message delivery and involves no established connections.
- A connection-oriented, reliable or unreliable, service. This kind of service establishes connections and may or may not ensure that messages are delivered.

Nowadays, most networks use an unreliable connectionless service at the network layer.

## Network Layer Organizations

There are two possible internal organizations of the network layer: **datagram** and **virtual circuits**.

### Datagram Organization

The datagram organization has been very popular in computer networks. Datagram-based network layers include **IPv4 and IPv6 in the global Internet**, CLNP defined by the ISO, IPX defined by Novell or XNS defined by Xerox.

This organization is **connectionless** and hence each packet contains:

- The network layer address of the destination host.
- The network layer address of the sender.
- The information to be sent.

Routers use **hop-by-hop** forwarding in the datagram organization. This means that when a router receives a packet that is not destined to itself, it looks up the destination address of the packet in its **forwarding table**.

> A **forwarding table** is a data structure that maps each destination address to the device. Then, a packet must be forwarded for it to reach its final destination.

Forwarding tables must:

- Allow any host in the network to reach any other host. This implies that **each router must know a route towards each destination**.
- The paths composed from the information stored in the forwarding tables **must not contain loops**. Otherwise, some destinations would be unreachable.

The **data plane** contains all the protocols and algorithms that are used by hosts and routers to create and process the packets that contain user data.

The **control plane** contains all the protocols and mechanisms that are used to compute, install, and maintain forwarding tables on the routers.

> **Routing tables** are generally used to generate the information for a forwarding table, which is a subset of the routing table. So, a routing table may have 3 paths for one source, and destination pair generated from a few different algorithms that’s perhaps also entered manually. The **forwarding table**, however, will only have one of those entries which is usually the preferred one based on another algorithm or criteria. The forwarding table is usually optimized for storage and lookup.

### Virtual Circuit Organization

The second organization of the network layer, called **virtual circuits**, has been _inspired by the organization of telephone networks_.

- Telephone networks have been designed to carry phone calls that usually last a few minutes.
- Each phone is **identified by a telephone number** and is attached to a **telephone switch**.
- To initiate a phone call, a telephone first needs to send the destination’s phone number to its local switch.
- The switch cooperates with the other switches in the network to create a bi-directional channel between the two telephones through the network.
- This channel will be used by the two telephones during the lifetime of the call and will be released at the end of the call.
- Until the 1960s, most of these channels were _created manually_, by telephone operators, upon request of the caller.
- Today’s telephone networks use automated switches and allow several channels to be carried _over the same physical link_, but the principles remain roughly the same.

In a network using virtual circuits, all hosts are **identified with a network layer address**. However, a host must explicitly request the establishment of a virtual circuit before being able to send packets to a destination host. The request to establish a virtual circuit is processed by the control plane, which installs state to create the virtual circuit between the source and the destination through intermediate routers.

This organization is **connection-oriented** which means that resources like buffers, CPU, and bandwidth are reserved for every connection. The first packet sent reserves these resources for subsequent packets, which all follow a single path for the duration of the connection.

The virtual circuit organization has been mainly used in public networks, starting from X.25, and then Frame Relay and Asynchronous Transfer Mode (ATM) network.

### Datagram vs Virtual Circuit Organization

#### Advantages of Datagram Organization

The main advantage of the datagram organization is that **hosts can easily send packets to any number of destinations**, while the virtual circuit organization requires the establishment of a virtual circuit before the transmission of a data packet. This can cause high overhead for hosts that exchange small amounts of data.

Another advantage of the datagram-based network layer is that **it’s resilient**. If a virtual or physical circuit breaks, it has to go through the connection establishment phase, again. In case of datagram-based network layer, **each packet can be routed independently of each other**, hop-by-hop, so intermediate routers can divert around failures.

#### Advantages of The Virtual Circuit Organization

On the other hand, the main advantage of the virtual circuit organization is that the **forwarding algorithm used by routers is simpler** than when using the datagram organization. Furthermore, the utilization of virtual circuits may allow the **load to be better spread through the network**.

Also, since the packets follow a particular dedicated path, they **reach the destination in the order they were sent**. Virtual circuits can be configured to provide a variety of services including best effort, in which case some packets may be dropped. However, in case of bursty traffic, there is a possibility of packet drops.

## Control Plane: Static and Dynamic Routing

The main purpose of the **control plane** is to maintain and build routing tables. This is done via a number of algorithms and protocols which we will discuss here.

### Static Routing

Manually computed routes are manually added to the routing table. This is useful if there are a few outgoing links from your network. It gets difficult when you have rich connectivity (in terms of the number of links to other networks). It also does not automatically adapt to changes – addition or removal of links or route. The disadvantages of this are:

1. The main drawback of static routing is that it doesn’t adapt to the evolution of the network and hence doesn’t scale well. When a new route or link is added, all routing tables must be recomputed.
2. Furthermore, when a link or router fails, the routing tables must be updated as well.

### Dynamic Routing

Unlike static routing algorithms, dynamic ones adapt routing tables with changes in the network. There are two main classes of dynamic routing algorithms: **distance vector** and **link-state routing algorithms**.

**Distance vector** is a simple distributed routing protocol. Distance vector routing allows routers to discover the destinations reachable inside the network as well as the shortest path to reach each of these destinations. The shortest path is computed based on the cost that is associated with each link.

Another way to create a routing table with the most efficient path between two routers or ‘nodes’ is by using **link-state routing**. Link-state routing works in two phases: **reliable flooding** and **route calculation**.

Routers running distance vector algorithms share summarized reachability information with their neighbors. Every router running link-state algorithms, on the other hand, builds a complete picture of the whole network (which is **phase I**) before computing the shortest path to all destinations. Then, based on this learned topology, each router is able to compute its routing table by using the shortest path computation such as _Dijkstra’s Algorithm_. This is **phase II**.

## Internet Protocol (IP)

The Internet Protocol (IP) is the network layer protocol of the TCP/IP protocol suite. The flexibility of IP and its ability to use various types of underlying data link layer technologies is one of its key advantages. The current version of IP is version 4 and is specified in [RFC 791](http://tools.ietf.org/html/rfc791.html).

## IP version 4 (IPv4)

The design of IPv4 was based on the following assumptions:

- IP should provide an _unreliable connectionless service_
- IP operates with the _datagram transmission mode_
- IP hosts must have _fixed size 32-bit addresses_
- IP must be _compatible with a variety of data link layers_
- IP hosts should be able to _exchange variable-length packets_

### Multihoming

An IPv4 address is used to identify an interface on a router or an interface on a host. A router has thus as many IPv4 addresses as the number of interfaces that it has in the data link layer. Most hosts have a single data link layer interface and thus have a single IPv4 address. However, with the growth of wireless more and more hosts have several data link layer interfaces (for example, an Ethernet interface and a WiFi interface). These hosts are said to be **multihomed**. A multihomed host with two interfaces has thus two IPv4 addresses.

### Address Assignment

Appropriate network layer address allocation is key to the efficiency and scalability of the Internet. A naive allocation scheme would be to provide an IPv4 address to each host when the host is attached to the Internet on a first come, first served basis.  Unfortunately, this would force all routers to maintain a specific route towards all approximately 1 Billion hosts on the Internet, which is not scalable. Hence, it’s important to minimize the number of routes that are stored on each router.

#### Subnetting

One solution is that routers should only maintain routes towards **blocks of addresses** and not towards individual hosts. For this, blocks of IP addresses are assigned to ISPs. The ISPs assign sub blocks of the assigned address space in a hierarchical manner. **These sub blocks of IP addresses are called subnets**. An IPv4 address is composed of two parts:

- A **subnetwork identifier** composed of the high order bits of the address.
- And a **host identifier** encoded in the lower order bits of the address.

#### Address Class

RFC 791 proposed to use the high-order bits of the address to encode the length of the subnet identifier. This led to the definition of three classes of addresses.

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

Read more about [IPv4](ipv4.md) especially about subnet masks, default subnet masks, variable-length subnets, network address and broadcast address.

Address Allocation, IPv4 Packets and IPv4 Packet Fragmentation and Reassembly will be discussed in another file: [ipv4.md](ipv4.md).

## Address Resolution Protocol (ARP)

While end hosts may use IP addresses to communicate with each other, the underlying data link layer uses its own naming schemes. So, end host interfaces have unique data link layer addresses. In order to get data to a host, a mechanism for converting IP addresses to the underlying data link layer address is needed. This entails that all **sending hosts must know the data link-layer address of their destination hosts** in order to send them a packet.

While manual configuration of the data link address of each host is possible in small networks, it does not scale. Hence, IPv4 hosts and routers must be able to _automatically_ obtain the data link layer address corresponding to any IPv4 address on the same LAN. This is the objective of the **Address Resolution Protocol (ARP)** defined in [RFC 826](http://tools.ietf.org/html/rfc826.html). ARP is a data link layer protocol and relies on the ability of the data link layer service to broadcast a frame to all devices attached to the same LAN.

The easiest way to understand the operation of ARP is:

- Assume that **host 10.0.1.22/24 needs to send an IPv4 packet to host 10.0.1.8**. To do so, the sending host must find the data link layer address that is attached to host 10.0.1.8.
- Each IPv4 host maintains an **ARP cache** that contains all mappings between IPv4 addresses and data link layer addresses that it knows.
- The sender, 10.0.1.22, first consults its ARP cache. As the cache does not contain the requested mapping, the **sender sends a broadcast ARP query frame on the LAN**.
- The frame contains:
  - the **data link layer address of the sender**
  - the **IPv4 address of the destination**
- This broadcast frame is received by all devices on the LAN. Every host upon receiving the ARP query inserts an entry for the sender’s IP address and data link layer address into their ARP cache.
- Every host on the LAN segment receives the ARP query however, **only the host that owns the requested IPv4 address replies by returning a unicast ARP reply frame with the requested mapping**.
- Upon reception of this reply, the **sender updates its ARP cache** and sends the IPv4 packet by using the data link layer service.

Note that to deal with devices that move or whose addresses are reconfigured, most ARP implementations remove the cache entries that have not been used for a few minutes. Some implementations also revalidate ARP cache entries from time to time by sending ARP queries.

## Dynamic Host Configuration Protocol

In the early days of the Internet, IP addresses were manually configured on both hosts and routers and almost never changed. However, this manual configuration can be complex and often causes errors that can be difficult to debug.

To ease the attachment of hosts to subnets, most networks now support the **Dynamic Host Configuration Protocol (DHCP)** [RFC 2131](http://tools.ietf.org/html/rfc2131.html). DHCP allows a host to automatically retrieve its assigned IPv4 address. A DHCP client actually can retrieve other network parameters too, including subnet mask, default gateway and DNS server addresses from the DHCP server.

- A DHCP server is associated with the subnet to which it is connected. Routers do not forward DHCP traffic from one subnet to another.
- Each DHCP server manages a pool of IPv4 addresses assigned to the subnet.
- When a host is first attached to the subnet, it sends a DHCP request message in a UDP segment to the DHCP server (the DHCP server listens on port 67).
  - As the host knows neither its own IPv4 address nor the IPv4 address of the DHCP server, this UDP segment is sent inside an IPv4 packet whose source and destination addresses are `0.0.0.0` and `255.255.255.255` respectively.
  - The DHCP request may contain options such as the data link layer address of the host.
- The server captures the DHCP request and selects an unassigned address in its address pool.
- It then sends the assigned IPv4 address in a DHCP reply message which contains:
  - The data link layer address of the host and additional information such as
    - The subnet mask of the IPv4 address
    - The address of the default router or the address of the DNS resolver.
  - The DHCP reply also specifies the lifetime of the address allocation. This forces the host to renew its address allocation once it expires.
- This DHCP reply message is sent in an IPv4 packet whose source and destination addresses are respectively the IPv4 address of the DHCP server and the `255.255.255.255` broadcast address.
- Thanks to the limited lease time, IP addresses are automatically returned to the pool of addresses when hosts are powered off. This reduces the waste of IPv4 addresses. Furthermore, the IP has to be renewed with the server every so often.

## IPv6

IPv4 was initially designed for a research network that would interconnect some research labs and universities. For this purpose, 32 bit addresses, or approximately 4.3 billion addresses seemed sufficient. Also, 32 bits was an incredibly convenient address size for software-based routers.

However, the popularity of the Internet, i.e., the number of smartphones and Internet of Things devices, was not anticipated. Nonetheless, we are running out of addresses. Hence, **IPv6 was designed to tackle these limitations of IPv4**.

Read more about IPv6 here: [ipv4.md](ipv4.md#ipv6)

## Middleboxes

When the TCP/IP architecture and the IP protocol were defined, two types of devices were considered in the network layer:

1. **End hosts** which are the sources and destinations of IP packets.
2. **Routers** that forward packets. When a router forwards an IP packet, it consults its forwarding table, updates the packet’s TTL, recomputes its checksum and forwards it to the next hop. A router **does not need to read or change the contents of the packet’s payload**.

However, in today’s Internet, there exist devices called middleboxes that are not strictly routers but which process, sometimes modify, and forward IP packets ([RFC 3234](http://tools.ietf.org/html/rfc3234.html)). Some middleboxes only operate in the network layer, but most middleboxes are able to analyze the payload of the received packets and extract the transport header, and in some cases the application layer headers.

### Firewalls

When the Internet was only a research network interconnecting research labs, security was not a concern. However, as the Internet grew in popularity, security concerns grew.

> The term **firewall** originates from a special wall used to confine the spread of fire in a building. It was also used to refer to a metallic wall between the engine compartment and the passenger area in a car. The purpose of this metallic wall is to prevent the spread of a fire in the engine compartment into the passenger area.

These security problems convinced the industry that their networks should be protected by special devices called **firewalls**. A typical firewall has two interfaces:

1. An external interface connected to the global Internet.
2. An internal interface connected to a trusted network.

#### Firewall Filters

The first firewalls included configurable **packet filters**. A packet filter is a set of rules defining the security policy of a network. In practice, these rules are based on the values of fields in the IP or transport layer headers. Any field of the IP or transport header can be used in a firewall rule, but the most common ones are:

- Filter on the source address
- Filter on the destination address
- Filter on the Protocol number found in the IP header
- Filter on the TCP or UDP port numbers
- Filter on the TCP flags

#### Stateless vs Stateful Firewalls

A firewall that does not maintain the state of flows passing through it is known as a **stateless firewall**. However, a **stateful firewall**, on the other hand, sees the first packet in a flow that is allowed by the configured security rules it creates a **session state** for it.

All subsequent packets belonging to that flow are allowed to go through. This filtering is more efficient compared to stateless firewalls that have to apply their rules to each and every packet. The flip side is the maintenance of state, which needs to be controlled.

#### Host-based vs Network-based Firewalls

Network-based firewalls are hardware based and generally deployed on the edge of a network. They are easy to scale and simple to maintain.

A host based firewalls, however, are software based and are deployed on end-systems. They are generally not easy to scale and require maintenance.

### Network Address Translation (NAT)

Network Address Translation (NAT) was proposed as a short term solution to deal with the expected shortage of IPv4 addresses in the late 1980s to early 1990s. Combined with CIDR, NAT helped to significantly slow down the consumption of IPv4 addresses. A NAT is a middlebox that interconnects two networks that are using IPv4 addresses from different addressing spaces. Usually, one of these addressing spaces is the public Internet while the other is using a private IPv4 address. Unlike a router, when a NAT box forwards traffic, it modifies the IP addresses in the IP header, as will be described shortly.

#### Broadband Access Routers

A very common deployment of NAT is in **broadband access routers**. The broadband access router interconnects a home network, either WiFi or Ethernet-based, and the global Internet via one ISP.

A single IPv4 address is allocated to the broadband access router and network address translation **allows all of the hosts attached to the home network to share a single public IPv4 address**.

#### Enterprise Networks

The second type of deployment is in enterprise networks. In this case, the NAT functionality is installed on a border router of the enterprise. A private IPv4 address is assigned to each enterprise host while the border router manages a **pool containing several public IPv4 addresses**.

#### Sending a Message over NAT

Sending a message:

- When the NAT receives the first packet from source S in the internal network which is destined to the public Internet, it creates a mapping between internal address S and the first address of its pool of public addresses (P1).
- Then, it translates the received packet so that it can be sent to the public Internet. This translation is performed as followed:
  - The source address of the packet (S) is replaced by the mapped public address (P1)
  - The checksum of the IP header is incrementally updated as its content has changed
  - If the packet carried a TCP or UDP segment, the transport layer checksum found in the included segment must also be updated as it is computed over the segment and a pseudo-header that includes the source and destination addresses.

Receiving a message:

- When a packet destined to P1 is received from the public Internet, the NAT consults its mapping table to find S.
- The received packet is translated and forwarded in the internal network.

This works as long as the pool of public IP addresses of the NAT does not become empty. In this case, a mapping must be removed from the mapping table to allow a packet from a new host to be translated. This garbage collection can be implemented by adding to each entry in the mapping table a timestamp that contains the last utilization time of a mapping entry. This timestamp is updated each time the corresponding entry is used. Then, the garbage collection algorithm can remove the oldest mapping entry in the table.

NAT allows many hosts to share one or a few public IPv4 addresses. However, using NAT has two important drawbacks.

1. First, it’s not easily possible for external hosts to open TCP connections with hosts that are behind a NAT. Some consider this to be a benefit from a security perspective. However, a NAT should not be confused with a firewall, as there are some techniques to traverse NATs.
2. Second, NAT breaks the end-to-end transparency of the network and transport layers.
