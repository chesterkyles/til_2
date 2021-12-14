# Dockerfile

Docker can build images automitaclly by reading the instructions from a `Dockerfile`. It is a text document taht contains all the commands a user could call on the command line to assemble an image.

## Format

Here is the format of the `Dockerfile`:

```dockerfile
# Comment
INSTRUCTION arguments
```

Note that the instruction is not case-senstitive; however, the convention is to use UPPERCASE to distinguish them from arguments more easily.

Read more about `format` here: <https://docs.docker.com/engine/reference/builder/#format>

## Parser Directives

Parser directives are optional. They do not add layers to the build, and will not be shown as a build step. They are written as special type of comment in the form `# directive=value`.

Read more about Parser Directives here: <https://docs.docker.com/engine/reference/builder/#parser-directives>

The following parser directives are supported:

- `syntax`
- `escape`

### Syntax

```dockerfile
# syntax=[remote image reference]
```

The syntax directive defines the location of the Dockerfile syntax that is used to build the Dockerfile. The BuildKit backend allows to seamlessly use external implementations that are distributed as Docker images and execute inside a container sandbox environment.

### Escape

```dockerfile
# escape=\ (backslash)

# escape=` (backtick)
```

The `escape` directive sets the character used to escape characters in a `Dockerfile`. If not specified, the default escape character is `\`. It is used both to escape characters in a line, and to escape a newline. This allows a `Dockerfile` instruction to span multiple lines.

Consider the following example which would fail in a non-obvious way on `Windows`. The second `\` at the end of the second line would be interpreted as an escape for the newline, instead of a target of the escape from the first `\`. Similarly, the `\` at the end of the third line would, assuming it was actually handled as an instruction, cause it be treated as a line continuation. The result of this dockerfile is that second and third lines are considered a single instruction:

```dockerfile
FROM microsoft/nanoserver
COPY testfile.txt c:\\
RUN dir c:\
```

Results in:

```txt
PS E:\myproject> docker build -t cmd .

Sending build context to Docker daemon 3.072 kB
Step 1/2 : FROM microsoft/nanoserver
  ---> 22738ff49c6d
Step 2/2 : COPY testfile.txt c:\RUN dir c:
GetFileAttributesEx c:RUN: The system cannot find the file specified.
PS E:\myproject>
```

## Environment Replacement

Environment variables (declared with the `ENV` statement) can also be used in certain instructions as variables to be interpreted by the `Dockerfile`.

Environment variables are notated in the `Dockerfile` either with `$variable_name` or `${variable_name}`. They are treated equivalently and the brace syntax is typically used to address issues with variable names with no whitespace, like `${foo}_bar`.

The `${variable_name}` syntax also supports a few of the standard bash modifiers as specified below:

- `${variable:-word}` indicates that if `variable` is set then the result will be that value. If `variable` is not set then `word` will be the result.
- `${variable:+word}` indicates that if `variable` is set then `word` will be the result, otherwise the result is the empty string.

In all cases, `word` can be any string, including additional environment variables.

Escaping is possible by adding a `\` before the variable: `\$foo` or `\${foo}`, for example, will translate to `$foo` and `${foo}` literals respectively.

```dockerfile
FROM busybox
ENV FOO=/bar
WORKDIR ${FOO}   # WORKDIR /bar
ADD . $FOO       # ADD . /bar
COPY \$FOO /quux # COPY $FOO /quux
```

## .dockerignore file

Before the docker CLI sends the context to the docker daemon, it looks for a file named `.dockerignore` in the root directory of the context. If this file exists, the CLI modifies the context to exclude files and directories that match patterns in it. This helps to avoid unnecessarily sending large or sensitive files and directories to the daemon and potentially adding them to images using `ADD` or `COPY`.

Here is an example:

```dockerignore
# comment
*/temp*
*/*/temp*
temp?
```

Docker also supports a special wildcard string `**` that matches any number of directories (including zero). Lines starting with `!` (exclamation mark) can be used to make exceptions to exclusions.

Detailed examples are shared on this link: <https://docs.docker.com/engine/reference/builder/#dockerignore-file>

## FROM

```dockerfile
FROM [--platform=<platform>] <image> [AS <name>]
# or
FROM [--platform=<platform>] <image>[:<tag>] [AS <name>]
# or
FROM [--platform=<platform>] <image>[@<digest>] [AS <name>]
```

The `FROM` instruction initializes a new build stage and sets the Base Image for subsequent instructions. As such, a valid `Dockerfile` must start with a `FROM` instruction.

- `ARG` is the only instruction that may precede `FROM` in the `Dockerfile`
- `FROM` can appear multiple times within a single `Dockerfile` to create multiple images or use on build stage as a dependency for another.
- Optionally, a name can be given to a new build stage by adding `AWS name` to the `FROM` instruction. This can be used in subsequent `FROM` and `COPY --from=<name>` instructions to refer to the image built in this stage.
- The `tag` or `digest` values are optional. If they are ommitted, the builder assumes a `latest` tag by default. The builder, however, returns an error if it cannot find the `tag` value.
- The optional `--platform` flag can be used to specify the platform of the image in case `FROM` references a multi-platform image. For example, `linux/amd64`, `limux/arm64`, or `windows/amd64`.

### Understand how ARG and FROM interact

`FROM` instructions support variables that are declared by any `ARG` instructions that occur before the first `FROM`.

```dockerfile
ARG  CODE_VERSION=latest
FROM base:${CODE_VERSION}
CMD  /code/run-app

FROM extras:${CODE_VERSION}
CMD  /code/run-extras
```

## RUN

This instruction has 2 forms:

- `RUN <command>` (_shell_ form, the command is run in a shell, which by default is `/bin/sh -c` on Linux or `cmd /S /C` on Windows)
- `RUN ["executable", "param1", "param2"]` (_exec_ form)

The `RUN` instruction will execute any commands in a new layer on top of the current image and commit the results. The resulting committed image will be used for the next step in the `Dockerfile`.

In the _shell_ form you can use a `\` (backslash) to continue a single `RUN` instruction onto the next line. For example, consider these two lines:

```dockerfile
RUN /bin/bash -c 'source $HOME/.bashrc; \
echo $HOME'

# or

RUN /bin/bash -c 'source $HOME/.bashrc; echo $HOME'

# or, in exec form, using different shell other than '/bin/sh'

RUN ["/bin/bash", "-c", "echo hello"]
```

Unlike the _shell_ form, the exec form does not invoke a command shell. This means that normal shell processing does not happen. For example, `RUN [ "echo", "$HOME" ]` will not do variable substitution on `$HOME`.  If you want shell processing then either use the _shell_ form or execute a shell directly, for example: `RUN [ "sh", "-c", "echo $HOME" ]`.

Note that the cache for `RUN` instructions isn’t invalidated automatically during the next build. The cache for an instruction like `RUN apt-get dist-upgrade -y` will be reused during the next build. The cache for `RUN` instructions can be invalidated by using the `--no-cache` flag, for example `docker build --no-cache`.

## CMD

This instruction has three forms:

- `CMD ["executable", "param1", "param2"]` (_exec_ form, preferred form)
- `CMD ["param1", "param2"]` (as _default parameters_ to _ENTRYPOINT_)
- `CMD command param1 param2` (_shell_ form)

There can only be one `CMD` instruction in a Dockerfile. If you list more than one `CMD` then only the last `CMD` will take effect.

**The main purpose of a `CMD` is to provide defaults for an executing container**. These defaults can include an executable, or they can omit the executable, in which case you must specify an ENTRYPOINT instruction as well.

If `CMD` is used to provide default arguments for the `ENTRYPOINT` instruction, both the `CMD` and `ENTRYPOINT` instructions should be specified with the JSON array format.

Note that same with `RUN` instruction, the _exec_ form does not invoke a command shell. Also, `ENTRYPOINT` is used in combination with `CMD` to have the container run the same executable every time.

## LABEL

```dockerfile
LABEL <key>=<value> <key>=<value> <key>=<value> ...
```

The `LABEL` instruction adds metadata to an image. A `LABEL` is a key-value pair. To include spaces within a `LABEL` value, use quotes and backslashes as you would in command-line parsing. A few usage examples:

```dockerfile
LABEL "com.example.vendor"="ACME Incorporated"
LABEL com.example.label-with-value="foo"
LABEL version="1.0"
LABEL description="This text illustrates \
that label-values can span multiple lines."
LABEL multi.label1="value1" multi.label2="value2" other="value3"
LABEL multi.label1="value1" \
      multi.label2="value2" \
      other="value3"
```

To view an image's labels, use `docker image inspect` command. Use `--format` option to show just the labels:

```sh
$ docker image inspect --format='' myimage

{
  "com.example.vendor": "ACME Incorporated",
  "com.example.label-with-value": "foo",
  "version": "1.0",
  "description": "This text illustrates that label-values can span multiple lines.",
  "multi.label1": "value1",
  "multi.label2": "value2",
  "other": "value3"
}
```

## EXPOSE

```dockerfile
EXPOSE <port> [<port>/<protocol>...]
```

The `EXPOSE` instruction informs Docker that the container listens on the specified network ports at runtime. You can specify whether the port listens on TCP or UDP, and the default is TCP if the protocol is not specified.

This instruction acts as a type of documentation only. It does not actually publish the port. To actually publish the port, use the `-p` flag on docker run to publish and map one or more ports, or the `-P` flag to publish all exposed ports and map them to high-order ports.

By default, `EXPOSE` assumes `TCP`. You can also specify `UDP`:

```dockerfile
EXPOSE 80/udp

# or to expose both

EXPOSE 80/tcp
EXPOSE 80/udp
```

## ENV

```dockerfile
ENV <key>=<value> ...
```

The `ENV` instruction sets the environment variable `<key>` to the value `<value>`. The value will be interpreted for other environment variables, so quote characters will be removed if they are not escaped. Examples are:

```dockerfile
ENV MY_NAME="John Doe"
ENV MY_DOG=Rex\ The\ Dog
ENV MY_CAT=fluffy

# or
ENV MY_NAME="John Doe" MY_DOG=Rex\ The\ Dog \
    MY_CAT=fluffy
```

The environment variables set using `ENV` will persist when a container is run from the resulting image. You can view the values using `docker inspect`, and change them using `docker run --env <key>=<value>`.

## ADD

This instruction has two forms:

```dockerfile
ADD [--chown=<user>:<group>] <src>... <dest>

# paths containing whitespace
ADD [--chown=<user>:<group>] ["<src>",... "<dest>"]
```

The `ADD` instruction copies new files, directories or remote file URLs from `<src>` and adds them to the filesystem of the image at the path `<dest>`. For example:

```dockerfile
# to <WORKDIR>/relativeDir/
ADD test.txt relativeDir/

# to /absoluteDir/
ADD test.txt /absoluteDir/
```

You may read more about different example for `ADD` instruction [here](https://docs.docker.com/engine/reference/builder/#add).

All new files and directories are created with a UID and GID of 0, unless the optional `--chown` flag specifies a given username, groupname, or UID/GID combination to request specific ownership of the content added. For example:

```dockerfile
ADD --chown=55:mygroup files* /somedir/
ADD --chown=bin files* /somedir/
ADD --chown=1 files* /somedir/
ADD --chown=10:11 files* /somedir/
```

### Notes

If the container root filesystem does not contain either `/etc/passwd` or `/etc/group` files and either user or group names are used in the `--chown` flag, the build will fail on the `ADD` operation. Using numeric IDs requires no lookup and will not depend on container root filesystem content.

If your URL files are protected using authentication, you need to use `RUN wget`, `RUN curl` or use another tool from within the container as the `ADD` instruction does not support authentication.

## COPY

This instruction has two forms:

```dockerfile
COPY [--chown=<user>:<group>] <src>... <dest>
COPY [--chown=<user>:<group>] ["<src>",... "<dest>"]
```

> The `--chown` feature is only supported on Dockerfiles used to build Linux containers, and will not work on Windows containers. Since user and group ownership concepts do not translate between Linux and Windows, the use of `/etc/passwd` and `/etc/group` for translating user and group names to IDs restricts this feature to only be viable for Linux OS-based containers.

It is somewhat the same as `ADD` instruction. You may read more about `COPY` instruction [here](https://docs.docker.com/engine/reference/builder/#copy).

## ENTRYPOINT

This instruction has two forms:

```dockerfile
# exec form
ENTRYPOINT ["executable", "param1", "param2"]

# shell form
ENTRYPOINT command param1 param2
```

An `ENTRYPOINT` allows you to configure a container that will run as an executable.

For example, the following starts nginx with its default content, listening on port 80:

```sh
docker run -i -t --rm -p 80:80 nginx
```

Command line arguments to `docker run <image>` will be appended after all elements in an _exec_ form `ENTRYPOINT`, and will override all elements specified using `CMD`. This allows arguments to be passed to the entry point, i.e., `docker run <image> -d` will pass the `-d` argument to the entry point. You can override the `ENTRYPOINT` instruction using the docker run `--entrypoint` flag.

The _shell_ form prevents any `CMD` or `run` command line arguments from being used, but has the disadvantage that your `ENTRYPOINT` will be started as a subcommand of `/bin/sh -c`, which does not pass signals. This means that the executable will not be the container’s `PID 1` - and will not receive Unix signals - so your executable will not receive a `SIGTERM` from `docker stop <container>`.

Only the last `ENTRYPOINT` instruction in the `Dockerfile` will have an effect.

### Exec form ENTRYPOINT example

You can use the exec form of `ENTRYPOINT` to set fairly stable default commands and arguments and then use either form of CMD to set additional defaults that are more likely to be changed.

```dockerfile
FROM ubuntu
ENTRYPOINT ["top", "-b"]
CMD ["-c"]
```

When you run the container, you can see that `top` is the only process:

```sh
$ docker run -it --rm --name test top -H

top - 08:25:00 up   7:27,   0 users,  load average: 0.00, 0.01, 0.05
Threads:   1 total,    1 running,    0 sleeping,   0 stopped,   0 zombie
%Cpu(s):  0.1 us,  0.1 sy,   0.0 ni,  99.7 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
KiB Mem:   2056668 total,   1616832 used,    439836 free,    99352 buffers
KiB Swap:  1441840 total,         0 used,   1441840 free.  1324440 cached Mem

  PID USER      PR  NI    VIRT     RES     SHR S %CPU %MEM     TIME+ COMMAND
    1 root      20  0    19744    2336    2080 R  0.0  0.1   0:00.04 top
```

To examine the result further, you can use `docker exec`:

```sh
$ docker exec -it test ps aux

USER     PID %CPU %MEM    VSZ   RSS TTY     STAT START   TIME COMMAND
root       1  2.6  0.1  19752  2352 ?       Ss+  08:24   0:00 top -b -H
root       7  0.0  0.1  15572  2164 ?       R+   08:25   0:00 ps aux
```

Read more examples here:

- <https://docs.docker.com/engine/reference/builder/#exec-form-entrypoint-example>
- <https://docs.docker.com/engine/reference/builder/#shell-form-entrypoint-example>

### Understand how CMD and ENTRYPOINT interact

Both `CMD` and `ENTRYPOINT` instructions define what command gets executed when running a container. There are few rules that describe their co-operation.

1. Dockerfile should specify at least one of `CMD` or `ENTRYPOINT` commands.
2. `ENTRYPOINT` should be defined when using the container as an executable.
3. `CMD` should be used as a way of defining default arguments for an `ENTRYPOINT` command or for executing an ad-hoc command in a container.
4. `CMD` will be overridden when running the container with alternative arguments.

The table below shows what command is executed for different `ENTRYPOINT` / `CMD` combinations:

-| No `ENTRYPOINT` | `ENTYRPOINT exec_entry p1_entry` | `ENTRYPOINT ["exec_entry", "p1_entry"]`
-| --------------- | -------------------------------- | --------------------------------------
No `CMD` | _error, not allowed_ | `/bin/sh -c exec_entry p1_entry` | `exec_entry p1_entry`
`CMD ["exec_cmd", "p1_cmd"]` | `exec_cmd p1_cmd` | `/bin/sh -c exec_entry p1_entry` | `exec_entry p1_entry exec_cmd p1_cmd`
`CMD ["p1_cmd", "p2_cmd"]` | `p1_cmd p2_cmd` | `/bin/sh -c exec_entry p1_entry` | `exec_entry p1_entry p1_cmd p2_cmd`
`CMD exec_cmd p1_cmd` | `/bin/sh -c exec_cmd p1_cmd` | `/bin/sh -c exec_entry p1_entry` | `exec_entry p1_entry /bin/sh -c exec_cmd p1_cmd`

> If `CMD` is defined from the base image, setting `ENTRYPOINT` will reset `CMD` to an empty value. In this scenario, `CMD` must be defined in the current image to have a value.

## VOLUME

```dockerfile
VOLUME ["/data"]
```

The `VOLUME` instruction creates a mount point with the specified name and marks it as holding externally mounted volumes from native host or other containers. The value can be a JSON array, `VOLUME ["/var/log/"]`, or a plain string with multiple arguments, such as `VOLUME /var/log` or `VOLUME /var/log /var/db`. For more information/examples and mounting instructions via the Docker client, refer to [Share Directories via Volumes](https://docs.docker.com/storage/volumes/) documentation.

The `docker run` command initializes the newly created volume with any data that exists at the specified location within the base image. For example, consider the following Dockerfile snippet:

```dockerfile
FROM ubuntu
RUN mkdir /myvol
RUN echo "hello world" > /myvol/greeting
VOLUME /myvol
```

This Dockerfile results in an image that causes `docker run` to create a new mount point at `/myvol` and copy the greeting file into the newly created volume.

### Notes about specifying volumes

Keep the following things in mind about volumes in the `Dockerfile`.

- **Volumes on Windows-based containers**: When using Windows-based containers, the destination of a volume inside the container must be one of:
  - a non-existing or empty directory
  - a drive other than `C:`
- **Changing the volume from within the Dockerfile**: If any build steps change the data within the volume after it has been declared, those changes will be discarded.
- **JSON formatting**: The list is parsed as a JSON array. You must enclose words with double quotes (`"`) rather than single quotes (`'`).
- **The host directory is declared at container run-time**: The host directory (the mountpoint) is, by its nature, host-dependent. This is to preserve image portability, since a given host directory can’t be guaranteed to be available on all hosts. For this reason, you can’t mount a host directory from within the Dockerfile. The `VOLUME` instruction does not support specifying a `host-dir` parameter. You must specify the mountpoint when you create or run the container.

You may want to read more about Dockerfile and instructions in the official documentation: <https://docs.docker.com/engine/reference/builder/>
