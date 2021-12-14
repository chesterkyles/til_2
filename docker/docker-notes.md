# Docker Notes

## Listening for Incoming Network Connections

By default, a container runs in isolation, and as such, it doesn’t listen for incoming connections on the machine where it is running. You must explicitly open a port on the host machine and map it to a port on the container.

Suppose we want to run the NGINX web server. It listens for incoming HTTP requests on port `80` by default. If we simply run the server, our machine does not route incoming requests to it unless I use the `-p` switch on the `docker run` command.

The `-p` switch takes two parameters: the **incoming port you want to open on the host machine**, and **the port to which it should be mapped inside the container**. For instance, here is how we can state that we want our machine to listen for incoming connections on port `8085` and route them to port `80` inside a container that runs NGINX:

```sh
docker run -d -p 8085:80 nginx
```

You can view the web page locally by running a browser and querying the server for `http://localhost:8085` URL.

## Using Volumes

When a container writes files, it writes them inside of the container. Which means that when the container dies (the host machine restarts, the container is moved from one node to another in a cluster, it simply fails, etc.) all of that data is lost. It also means that if you run the same container several times in a load-balancing scenario, each container will have its own data, which may result in inconsistent user experience.

A rule of thumb for the sake of simplicity is to ensure that containers are stateless, for instance, storing their data in an external database (relational like an SQL Server or document-based like MongoDB) or distributed cache (like Redis). However, sometimes you want to store files in a place where they are persisted; this is done **using volumes**.

Using a volume, you map a directory inside the container to a persistent storage. Persistent storages are managed through drivers, and they depend on the actual Docker host. They may be an Azure File Storage on Azure or Amazon S3 on AWS. With Docker Desktop, you can map volumes to actual directories on the host system; this is done using the `-v` switch on the `docker run` command.

Suppose you run a MySQL database with no volume:

```sh
docker run -d mysql:5.7
```

Any data stored in that database will be lost when the container is stopped or restarted. In order to avoid data loss, you can use a volume mount:

```sh
docker run -v /your/dir:/var/lib/mysql -d mysql:5.7
```

It will ensure that any data written to the `/var/lib/mysql` directory inside the container is actually written to the `/your/dir` directory on the host system. This ensures that the data is not lost when the container is restarted.

## Pull Images from Registry

Each container is created from an image. You provide the image name to the `docker run` command. Docker first looks for the image locally and uses it when present. When the image is not present locally, it is downloaded from a **registry**.

When an image is published to a registry, its name must be:

```txt
<repository_name>/<name>:<tag>
```

- `tag` is optional; when missing, it is considered to be _latest_ by default
- `repositorny_name` can be a registry DNS or the name of a registry in the [Docker Hub](https://hub.docker.com/search?type=image).

All of the images we’ve been using until now were downloaded from Docker Hub as they are not DNS names. For instance, the [Jenkins image](https://hub.docker.com/_/jenkins) may be found on the Docker Hub.

Although the `docker run` command downloads images automatically when missing, you may want to trigger the download manually. To do this, you can use the `docker pull` command. A pull command forces an image to download, whether it is already present or not.

Here are some scenarios where using a `docker pull` command is relevant:

- You expect that the machine which runs the containers does not have access to the registries (e.g., no internet connection) at the time of running the containers.
- You want to ensure you have the latest version of an image tagged as “latest,” which wouldn’t be downloaded by the `docker run` command.

## Add Tags to Image

### The _latest_ tag

As long as you are creating simple software, running on a simple CI/CD pipeline, it can be fine to use the _latest_ tag. In a simple scenario, you may:

1. Update the source code
2. Build a new image with the _latest_ tag
3. Run a new container with the newest image
4. Kill the previous container

There’s a caveat with this however: when using the `docker run hello` command on a distant machine (which actually means `docker run hello:latest`), the distant machine has no means to know that there is a newer version of the `hello:latest` image. You need to run the `docker pull hello` command on the distant machine in order for the newest version of your image to be downloaded to that machine.

This may sound awkward, and that’s one reason for not just using the _latest_ tag.

### Reason to Tag Images

Other reasons come to mind once you become more serious with your CI/CD pipeline. For instance, you may want any or all of the following features:

- Be able to roll back to a previous version of an image if you detect a problem with the latest image.
- Run different versions in different environments. For instance, the latest version in a test environment and the previous version in a production environment.
- Run different versions at the same time, routing some users to the latest version and some to the previous versions. This is known as a **canary release**.
- Deploy different versions to different users, and be able to run whatever version on your development machine while you support them.

These are all good reasons for tagging your images. If you ensure each released image has a different tag, you can run any of the scenarios mentioned above.

You’re free to tag your images however you want. Common tags include:

- a version number, e.g. `hello:1.0`, `hello:1.1`, `hello:1.2`
- a Git commit tag, e.g. `hello:2cd7e376`, `hello:b43a14bb`

### Tags for Base Images

Remember your images are based on other images; this is done using the `FROM` instruction in your `Dockerfile` file. Just as you can tag your images, the base image you use can be the latest one or a tagged one.

It’s quite tempting to base your images on the latest ones so that you’re always running on up-to-date software, especially since it’s so straightforward. You could be tempted to use the following instruction in your `Dockerfile` file:

```dockerfile
FROM nginx:latest
```

This is not a good idea! First of all, it doesn’t mean that any running container will be based on the `latest` available version of the `nginx` image. Docker is about having reproducible images, so the `latest` version is evaluated when you build your image, not when the container is run. This means that the version will not change unless you run the `docker build` command again.

Second, you’re likely to run into trouble. What about the `nginx` image releasing a new version with breaking changes? If you build your image again, you’re likely to get a broken image.

For these reasons, it is recommended to specify the image tag. If you want to keep up to date with new releases of the base image, update the tag manually and make sure you test your image before releasing it.

## Publishing an Image

Whichever Registry you choose, publishing an image is a three-step process:

1. Build your image (`docker build`) with the appropriate prefix name or tag (`docker tag`) an existing one appropriately.
2. Log into the Registry (`docker login`).
3. Push the image into the Registry (`docker push`).

**Docker Hub** is a Docker Registry offered by Docker Inc. It allows unlimited storage of public images, and paid plans to host your private images. A public image may be accessed by others, which is precisely what you want when you make your software widely available - less for internal enterprise software.

Public registries are a convenient way to share your Docker images, but you might want to keep some images available only to yourself, your company, or your organization. Private registries ensure that you can keep your private images private.

There are many ways to get a private registry:

- **Docker Hub**, where you pay according to the number of private repositories used.
- **Azure Container Registry** allows you to have your own private registry in Azure.
- **GitLab** has an included optional Docker registry; enable it so that each project can store the images it creates.
- The **[registry](https://hub.docker.com/_/registry) image** allows you to host your own registry on a Docker enabled machine as a container.

## Size Matters

When you create an image, you want it to be as small as possible for several reasons:

- Reduce pull and push times
- Use a minimum amount of space in the Registry
- Use a minimum amount of space on the machines that will run the containers
- Use a minimum amount of space on the machine that creates the image

In order to reduce the size of an image, you need to understand that it is influenced by several factors:

- The files included in your image
- The base image size
- Image layers

### Files Included in the Image

You may want to exclude files from copy (`COPY` instruction). You can use a `.dockerignore` file for that purpose. Simply add a `.dockerignore` file at the root of your build context that lists files and folders that should be excluded from the build like a `.gitignore` file.

Here is an example `.dockerignore` file:

```sh
# Ignore .git folder
.git
# Ignore Typescript files in any folder or subfolder
**/*.ts
```

### Base Image Size

The base image you choose in your `FROM` instruction at the top of your `Dockerfile` file is part of the image you build.

There are optimizations in which a machine will not pull the base image when pulling your image, as long as it already pulled that base image before. But oftentimes such optimizations cannot run, so it’s better to reference small base images.

For instance, use `debian:8-slim` which weighs `79.3 MB` instead of `debian:8` which weighs `127 MB`.

### Image Layers

When creating an image, Docker reads each instruction in order and the resulting partial image is kept separate; it is cached and labeled with a unique ID. Such caching is very effective because it is used at different moments of an image life:

- In a future build, Docker will use the cached part instead of recreating it as long as it is possible.
- When pushing a new version of the image to a Registry, the common part is not pushed.
- When pulling an image from a registry, the common part you already have is not pulled.

The caching mechanism can be summed up as follows: when building a new image, Docker will try its best to skip all instructions up to the first instruction that actually changes the resulting image. All prior instructions result in the cached layers being used.

Change the order of those instructions yields a fantastic boost in using caching. In order to benefit from caching, do the steps in the Dockerfile that are likely to change, or have their input change, as late as possible.

## Multi-Stage Dockerfiles

Consider the `Dockerfile` below:

```dockerfile
FROM microsoft/dotnet:2.2-sdk AS builder
WORKDIR /app

COPY . .
RUN dotnet restore
RUN dotnet publish --output /out/ --configuration Release

EXPOSE 80
ENTRYPOINT ["dotnet", "aspnet-core.dll"]
```

The problem with the image above is that it’s massive; it’s `1730 MB`! This is because it contains the build tools we don’t need, tools like `dotnet restore` and `dotnet publish`. Also, it contains the source code and intermediate build artifacts.

We could use the `RUN` command to try and clean the image; delete intermediate build artifacts, uninstall build tools, and delete source code, but that would be tedious. Remember that containers are like cheap, disposable machines; let’s dispose of the build machine and grab a brand new one that has only the runtime installed!

Docker has a neat way to do this; use a single `Dockerfile` file with distinct sections. An image can be named simply by adding AS at the end of the FROM instruction. Consider the following simplified `Dockerfile` file:

```dockerfile
FROM fat-image AS builder

...

FROM small-image
COPY --from=builder /result .

...
```

It defines two images, but only the last one will be kept as the result of the `docker build` command. The filesystem that has been created in the first image, named `builder`, is made available to the second image thanks to the `--from` argument of the `COPY` command. It states that the `/result` folder from the builder image will be copied to the current working directory of the second image.

This technique allows you to benefit from the tools available in `fat-image` while getting an image with only the environment defined in the `small-image` it’s based on. Moreover, you can have many stages in a Dockerfile file when necessary.

```dockerfile
FROM microsoft/dotnet:2.2-sdk AS builder
WORKDIR /app

COPY *.csproj  .
RUN dotnet restore

COPY . .
RUN dotnet publish --output /out/ --configuration Release

FROM microsoft/dotnet:2.2-aspnetcore-runtime-alpine
WORKDIR /app
COPY --from=builder /out .
EXPOSE 80
ENTRYPOINT ["dotnet", "aspnet-core.dll"]
```

After building the image from that multi-stage definition, we get an image that weights only `161 MB`. That’s a **91%** improvement over the image size!

## Disk Space Consumption

Creating images and running containers consumes disk space that later on you might want to reclaim. Here are some ways disk space is consumed unknowingly:

- Stopped containers that were not removed by using the `--rm` switch on the `docker run` command or using the docker rm command once they are stopped.
- Unused images: images that are not referenced by other images or containers.
- Dangling images: images that have no name. This happens when you docker build an image with the same tag as before, the new one replaces it and the old one becomes dangling.
- Unused volumes.

Manually removing these, one by one, can be tedious, but there are garbage collection commands that can help with that.

Here are the commands you can run to remove the items that you don’t need:

```sh
docker container prune -f
docker volume prune -f
docker image prune -f
```

To remove all unused images:

```sh
docker image prune --all
```
