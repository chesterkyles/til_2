# cURL commands

cURL is a command-line tool that transfers data to or from a server. The transfer can be based on a vast set of protocols. cURL stands for "Client URL".

Link: <https://curl.se/docs/manpage.html>

## Basic Syntax

```sh
curl [options] [URL]
```

## Sending GET Request

```sh
curl --request GET [URL]
# curl -X GET [URL]
# curk [URL]
```

### With Username and Password

```sh
curl --request GET [URL] --user [username]:[password]
```

## Sending POST Request

### With JSON data

```sh
curl --request POST [URL] --header "Content-Type: application/json" --data '{"username":"xyz","password":"xyz"}'
# curl -X POST [URL] -H "Content-Type: application/json" -d '{"username":"xyz","password":"xyz"}'
```

## Options

```sh
 - v , --verbose # Verbose option.
 - s , --silent # Disable progress meter
 - I , --head # Send an HTTP request with the `head` method
```
