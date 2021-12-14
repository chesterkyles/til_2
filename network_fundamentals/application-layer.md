# Application Layer

## Key Responsibilities

The main job of the application layer is to enable end-users to access the Internet via a number of applications. This involves:

- Writing data off to the network in a format that is compliant with the protocol in use.
- Reading data from the end-user.
- Providing useful applications to end users.
- Some applications also ensure that the data from the end-user is in the correct format.
- Error handling and recovery is also done by some applications.

## Network Application Architectures

### Client-Server Architecture

In this architecture, a network application consists of two parts: **client-side** software and **server-side** software. These pieces of software are generally called **processes** and they communicate with each other through **messages**.

#### Servers

The server process controls access to a centralized resource or service such as a website. Servers have two important charactersistics:

1. Generally, an attempt is made to keep server online all the time, although 100% availability is impossible. Furthermore, servers set up as a hobby or as an experiment may not need to be kept online. Nevertheless, the client must be able to find the server online when needed, otherwise, communication wouldn't take place.
2. They have at least one reliable IP address with which they can be reached.

#### Clients

Client processes use the Internet to consume content and use the services. Client processes almost always initiate connection to server, while server processes wait for requests from clients.

### Data Centers

When client-server applications scale, one or even two servers can't handle the requests from a large number of clients. Additionally, servers may crash due to any reason and might stop working. Most applications have serveral servers in case one fails. Therefore, several machines host server processes (called servers too), and they reside in **data center**.

### Peer-to-Peer Architecture (P2P)

In this architecture, applications on end-systems called 'peers' communicate with each other. No dedicated server or large data is involved. Peers mostly reside on PCs like laptops and desktops in homes, offices, and universitites. The key advantage is that it can scale rapidly - without the need of spending large amounts of money, time or effort.

Regardless of P2P's decentralized nature, each peer can be categorized as servers or clients i.e., every machine is capable of being a client as well as a server.

## Program vs Process vs Thread

- A **program** is simply an executable file. An application such as MS Word is one example.
- A **process** is any currently running instance of a program. So one program can have several copies of it running at once. One MS Word program can have multiple open windows.
- A **thread** is a lightweight process. One process can have multiple running threads. The difference between threads and processes is that threads do lightweight singular jobs.

## Sockets

Processes on different machines send messages to each other through the computer network. The _interface_ between a process and the computer network is called a **socket**. Note that sockets do not have anything to do with hardware - they are software interfaces.

## Addressing

Messages have to be addressed to a certain application on a certain end system. It is done via addressing constructs like **IP addresses and ports**.

Since every end-system may have a number of applications running, **ports** are used to address the packet to specific applications. Some ports are reserved such as port 80 for HTTP and port 443 for HTTPS.

[Ephemeral Ports](https://en.wikipedia.org/wiki/Ephemeral_port): Different port numbers are dynamically generated for each instance of an application. The port is freed once the application is done using it.

Furthermore, server processes need to have well defined and fixed port numbers so that clients can connect to them in a systematic and predictable way. However, clients don't need to have reserved ports. They can use ephemeral ports.

## HTTP (HyperText Transfer Protocol)

- Web pages are objects that consists of other objects.
- An object is simply a file like an HTML file, PNG file, MP3 file, etc.
- Each object has a URL.
- The base object of a web page is often an HTML file that has references to other objects by making requests for them via their URL.

A **URL** is used to locate files that exists on servers. URLs consist of the following parts:

- Protocol in use
- The hostname of the server
- The location of the file
- Arguments to the file

HTTP is a client-server protocol hat specifies how Web clients request Web pages from Web servers and how Web servers send them.

```txt
CLIENT  --> HTTP Request ----> SERVER
CLIENT  <-- HTTP Response <--- SERVER
```

There is a whole class of protocols that are considered **request-response protocols**. HTTP is one of them. Note that HTTP is a **stateless protocol**: servers do not store any information about clients by default. So if a client requests the same object multiple times in a row, the server would send it and would not know that the same client is requesting the same object repeatedly.

### HTTP Requires Lower Layer Reliability

- Application layer protocols rely on underlying transport layer protocols called **UDP** (User Datagram Protocol) and **TCP** (Transmission Control Protocol).
- **TCP ensures that messages are always delivered**. Messages get delivered in the order that they are sent.
- **UDP does not ensure that messages get delivered**. This means that some messages may get dropped and so never be received.
- **HTTP uses TCP** as its underlying transport protocol so that messages are guaranteed to get delivered in order. This allows the application to function without having to build any extra reliability as it would've had to with UDP.
- **TCP is connection-oriented**, meaning a connection has to be initiated with servers using a series of starting messages.
- Once the connection has been made, the client exchagnes messages with the server until the connection is officially closed by sending a few ending messages.

There are two types of HTTP Connections:

- **Non-persistent HTTP connections**
- **Persistent HTTP connections**

#### Non-persistent HTTP Connections

These use **one TCP connection per request**. Assume a client requests the base HTML file of a web page. The following are what happens:

1. The client initiates a TCP connection with a server
2. The client sends an HTTP request to the server
3. The server retrieves the requested object from its storage and sends it
4. The client receives the object which in this case is an HTML file. If that file has references to more objects, steps 1-4 are repeated for each of those
5. The server closes the TCP connection

#### Persistent HTTP

An HTTP session typically involves multiple HTTP request-response pairs, for which separate TCP connections are established and then torn down between the same client and server. This is ineffienct. Persistent HTTP was developed, which used a single client-server TCP connection for all the HTTP request-responses for a session.

### HTTP Request Messages

Below is an example of a typical HTTP message:

```txt
GET /path/to/file/index.html HTTP/1.1
Host: www.educative.io
Connection: close
User-agent: Mozilla/5.0
Accept-language: fr
Accept: text/html
```

It should be noted that:

- HTTP messages are in plain ASCII text
- Each line of the message ends with two control characters: a carriage return and a line feed: `\r\n`
- This particular message has 6 lines, but HTTP messages can have one or as many lines as needed
- The first line is called the request line while the rest are called header lines

#### HTTP Methods

HTTP methods tell the server what to do. There are a lot of HTTP methods but the most common ones are: `GET`, `POST`, `HEAD`, `PUT`, or `DELETE`.

- `GET` is the most common and requests data
- `POST` puts an object on the server
  - This method is not used when the client is not sure where the new data would reside. The server responds with the location of the object.
  - The `POST` method technically requests a page but that depends on what was entered.
- `HEAD` is similar to `GET` method except that the resource requested does not get sent in response. Only the HTTP headers are sent instead
  - This is useful for quickly retrieving meta-information written in response headers, without having to transport the entire content. In other words, it's useful to check with minimal traffic if a certain object still exists. This includes its meta-data, like the last modified data. The latter can be useful for caching.
  - This is also useful for testing and debugging.
- `PUT` uploads an enclosed entity under a supplied URI. In other words, it puts data at a specific location. If the URI refers to an already existing resource, it's replaced with the new one. If the URI does not point to an existing resource, then the server creates the resource with that URI.
- `DELETE` deletes an object at a given URL.

Notes:

- **Uniform Resource Locators (URLs)**: URLs are used to identify an object over the web. [RFC 2396](https://tools.ietf.org/html/rfc2396). A URL has the following format: `protocol://hostname:port/path-and-file-name`
- **Uniform Resource Identifiers (URIs)** can be more specific than URLs in such a way that they can locate fragments within objects too [RFC 3986](https://datatracker.ietf.org/doc/html/rfc3986). A URI has the following format: `http://host:port/path?request-parameters#nameAnchor`. For instance, `https://www.educative.io/collection/page/10370001/6105520698032128/6460983855808512/#http-methods` is a URI.

#### HTTP Header lines

The HTTP request line is followed by an HTTP header. You can read further about HTTP headers here: <https://en.wikipedia.org/wiki/List_of_HTTP_header_fields>

- The first header line specifiec the `Host` that the request is for.
- The second one defines the type of HTTP `Connection`. It's Non-persistent in the case of the above example as the connection is specified to be closed.
- The `user-agent` line specified the client. This is useful when the server has different web pages that exist for different devices and browsers.
- The `Accept-language` header specifies the language that is preferred. The server checks if a web page in that language exists and sends it if it does, otherwise the server sends the default page.
- The `Accept` header defines the sort of response to accept. It can be anything like HTML files, images, and audio/video.

### HTTP Response Messages

```txt
HTTP/1.1 200 OK
Connection: close
Date: Tue, 18 Aug 2015 15: 44 : 04 GMT
Server: Apache/2.2.3 (CentOS)
Last-Modified: Tue, 18 Aug 2015 15:11:03 GMT
Content-Length: 6821
Content-Type: text/html

[The object that was requested]
```

The HTTP response example above has three parts: an initial status line, some header lines, and an entity body. Note that HTTP response messages don't have the URL or the method fields since those are strictly for request messages.

#### Status Line

- HTTP response status line start with the HTTP version
- The status code comes next which tells the client if the request succeeded or failed
- There are a lot of status codes:
  - 1xx codes fall in the informational category
  - 2xx codes fall in the success category
  - 3xx codes are for redirection
  - 4xx is client error
  - 5xx is server error

Here is a list of some common status codes and their meanings:

- `200 OK`: the request was successful, and the result is appended with the response message.
- `404 File Not Found`: the requested object doesn't exist on the server.
- `400 Bad Request`: the generic error code that indicates that the request was in a format that the server could not comprehend.
- `500 HTTP Internal Server Error`: the request could not be completed because the server encountered some unexpected error.
- `505 HTTP Version Not Supported`: the requested HTTP version is not supported by the server.

Have a look at pages 39 and 40 of [RFC 2616](https://www.ietf.org/rfc/rfc2616.txt) for a comprehensive list.

#### Header Lines

- Connection type: in the case of above example, it indicates that the server will `close` the TCP connection after it sends the response
- Date: the date at which the response was generated
- Server: gives server software specification of the server that generated the message. Apache in the above example
- Last-Modified: the date on which the object being sent was last modified
- Content-Length: the length of the object being sent in 8-bit bytes
- Content-Type: the type of content. the type of file is not determined by the file extension of the object, but by this header

The **response body** contains the file requested.

## Cookies

HTTP is a stateless protocol, but we often see websites where session state is needed. For instance, imagine you are browsing for products on an e-commerce website. How does the server know if you are logged in or not, or if the protocol is stateless? Cookies allow the server to keep track of this sort of information.

### How Cookies Work

- Cookies are unique string identifiers that can be stored on the client's browser.
- These identifiers are set by the server through HTTP headers when the client first navigates to the website.
- After the cookie is set, it's sent along with subsequent HTTP requests to the same server. This allows the server to know who is contacting it and hence serve content accordingly.

So, the HTTP request, the HTTP response, the cookie file on the client's browser, and a database of cookie-user values on the server's end are all involved in the process of setting and using cookies.

### `Set-cookie` Header

When a server wants to set a cookie on the client-side, it includes the header `Set-cookie: value` in the HTTP response. This value is then appended to a special cookie file stored on your browser. The cookie file contains:

- The website's domain
- The string value of the cookie
- The date that the cookie expires

### The Dangers of Cookies

While cookies seem like a great idea to make HTTP persistent when needed, cookies have been severly abused in the past.

If a website has stored a cookie on your browser, it knows exactly when you visit it, what pages you visit and in what order. This itself makes some people uncomfortable.

Also, websites may not necessarily know personally identifiable information about you such as your name, and they may only know the value of your cookie. But what if websites can track what you do on other websites? Well, they can. This is known as third-party cookies. Third-party cookies are cookies set for domains that are not being visited.

The following link tells you how to view cookies in Chrome: <https://developer.chrome.com/docs/devtools/storage/cookies/>

## DNS (Domain Name System)

We find things on the internet, generally, using one of the following ways:

- Addresses or locations that specify where something is
- Names, in particular, domain names, or the unique name that identifies a websites which are mapped into IP addressed based on lookup servic that uses a database
- Content-based addressing

DNS is a client-server application layer protocol that translates hostnames on the Internet to IP addresses. At the core, the Internet operates on IP address, but these are difficult to remember for humans. So, DNS names are prefereably used at the application layer for which the DNS provides a mapping to IP addresses.

### Distributed Hierarchical Database

One single database on one single server does not scale for reasons such as:

- Single point of failure. If the server that has the database crashes, DNS would stop working entirely, which is too risky.
- Massive amounts of traffic. Everyone would be querying that one server. It will not be able to handle that amount of load.
- Maintenance. Maintaining the server would become critical to the operation of DNS.
- Location. Where would the server be located?

These are the reasons why DNS employs several servers, each with part of the database. Also, the serers exist in a hierarchy. To understand this better, it is important to understand how URLs are broken down into their hierarchies. For example:

```txt
URL: discuss.educative.io

discuss -----> sub-domain
educative ---> second-level domain
io ----------> top-level domain
```

#### Root DNS Servers

Root DNS servers are the first point of contact for a DNS query. They exist at the top of the hierarchy and point to the appropriate TLD (top-level domain) server in reply to the query. So for the example above, it would return the IP address of a server for the top-level domain `io`.

#### Top-level Servers

Servers in the top-level domain hold mappings to DNS servers for certain domains. Each domain is meant to be used by specific organizations only. Here are some common domains:

- `com` : This TLD was initially meant for **commercial** organizations only - but it has now been opened for general use
- `edu` : Used by educational institutions
- `gov` : Only used by the US government
- `mil` : Used by US military organizations
- `net` : It was initially intended for use by organizations working in network technology such as ISPs, but it is now a general purpose domain like `com`
- `org` : This domain was intended for non-profit organizations but has been opened for general use now
- `pk`, `uk`, `us`, `...` : Country suffixes

Today, the set of top-level domain names is managed by the [Internet Corporation for Assigned Names and Numbers (ICANN)](https://www.icann.org/)

#### Authorative Servers

Every organization with a public website or email server provides DNS records. These records have hostnames to IP address mappings stored for that organization. These records can either be stored on a dedicated DNS server for that organization or they can pay for a service provided to store the records on their server.

#### Local DNS Cache

- DNS mappings are often also cached locally on the client end-system to avoid repetitive lookups and saves time for often visited websites.
- This is done via an entity called the **local resolver library**, which is part of the OS. The application makes an API call to this library. This library manages the local DNS cache.
- If the local resolver library does not have a cached answer for the client, it will contact the organization's local DNS server.
- This local DNS server is typically configured on the client machine either using a protocol called **DHCP** or can be configured statically.
- So, if it's configured manually, any local DNS server of the client's choice can be chosen. A few open DNS servers are incredibly popular such as the ones by Google.

#### Local DNS Servers

Local DNS servers are usually the first point of contact after a client checks its local cache. These servers are generally hosted at the ISP and contain some mappings based on what websites user visit.

**Security Warning**: ISPs have a record of which IP address they assigned to which customers. Furthermore, their DNS server has the IP addresses of who contacts it and what hostname they were trying to resolve. So your ISP technically has a record of all of the websites you visit. If this makes you uncomfortable, you can change your DNS server to any open public DNS server.

### Resource Records

The DNS distributed database consists of entities called **Resource Records**. RRs contain some or all of the following values:

- **Name** of the domain
- **Resource data (RDATA)** provides information appropriate for the type of the resource record
- **Type** of the resource record
- **Time-to-live (TTL)** is how long the record should be cached by the client in seconds
- **DNS Class** : There are many types of class but we're mainly concerned with `IN` which implies the `Internet` class. Another common value for the DNS Class is `CH` for `CHAOS`. The `CH` class is mostly used for things like querying DNS server versions

#### Types of resource records

- **Address** type or `A` addresses contain IPv4 address to hostname mappings. They consist of:
  - The `name` is the hostname in question.
  - The `TTL` in seconds.
  - The `type` which is `A` in this case.
  - The `RDATA` which in this case is the IP address of the domain
- **Canonical name** or `CNAME` records are records of alias hostnames against actual hostname. For example if, `ibm.com` is really `servereast.backup2.com`, then the latter is the canoncal name of `ibm.com`.
  - The `name` is the alias name for the real or 'canonical' name of the server.
  - The `RDATA` is the canoncal name of the server
- **Mail Exchanger** or `MX` records are records of the server that accepts email on behalf of a certain domain.
  - The `name` is the name of the host.
  - The `RDATA` is the name of the mail server associated with the host.

These resource records are stored in text form in special files called **zone files**.

### DNS Messages

There are few kinds of DNS messages, out of which the most common are **query** and **reply**, and both have the same format.

There are also **zone transfer request and response**. But, those are not used by common clients. Backup or secondary DNS servers use them for **zone transfers**, which are when zone files are copied from one server to another. This takes place over TCP.
