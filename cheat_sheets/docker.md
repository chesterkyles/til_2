# Docker

## Common Commands for Container Management

### Run image/container

```sh
docker run <image>
```

To disconnect while allowing the long-lived container to continue running in the background, we use the `-d` or `–-detach` switch on the `docker run` command.

```sh
docker run --rm -it -p <src_port:dest_port> <image>
```

- The `-it` switch allows to stop the container using `Ctrl-C` from the command-line
- The `–rm` switch ensures that the container is deleted once it has stopped

```sh
docker run <container> -e <name=value>
```

In order to provide an environment variable’s value at runtime, you simply use the `-e name=value` parameter on the `docker run` command.

```sh
docker run -d --restart always <image>
docker run -d --restart unless_stopped <image>
```

When creating a container, you have the choice to set a restart mode. It tells Docker what to do when a container stops. A restart mode is set with the `--restart` switch. `always` option will always run the container and will not stop with `docker stop` command.

If you want your container to always be running except when you explicitly stop it, use the `unless_stopped` restart mode.

### List running containers

```sh
docker ps
```

Add the `-a` switch in order to see containers that have stopped

### View images available locally

```sh
docker image ls
```

### Remove images from local machine

```sh
docker rmi <name|id>
```

### Retrieves the logs of a container

```sh
docker logs <container>
```

We can get a portion of the output using the `–-from`, `–-until`, or `–-tail` switches.

### Gets detailed information

```sh
docker inspect <name|id>
```

This command will return low-level information on Docker objects. It will get detailed information about a running or stopped container.

### Stop a running container

```sh
docker stop <container>
```

You can add one or more containers to stop using `docker stop <container> <container...>`.

### Delete a container

```sh
docker rm <container>
```

You can add one or more containers to remove using `docker rm <container> <container...>`.

### Remove all stopped containers

```sh
docker container prune -f
```

The `-f` switch is an implicit confirmation to proceed and delete all stopped containers right away, instead of asking to confirm that operation.

To remove dangling images:

```sh
docker container prune -f
docker volume prune -f
docker image prune -f
```

To remove all unused images:

```sh
docker image prune --all
```

### Monitoring

```sh
docker stats
```

This will output a live list of running containers plus information about how many resources they consume on the host machine. Like a `docker ps` extended with live resource usage data.

### Create an image from a `Dockerfile`

```sh
docker build -t <name> .
```

The `-t` switch is used in front of the desired image. An image can be created without a name, it would have an auto-generated unique ID, so it is an optional parameter on the `docker build` command.

> Note the dot at the end of the command above. It specifies which path is used as the build context (more about that later), and where the `Dockerfile` is expected to be found. Should the `Dockerfile` have another name or live elsewhere, we can add a `-f` switch in order to provide the file path.
