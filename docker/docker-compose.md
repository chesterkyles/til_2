# Docker Compose

Docker-compose is a tool that combines and runs multiple containers of interrelated services with a single command. It is a tool to define all the applications dependencies in one place and let the Docker take care of running all of those services in just one simple command `docker-compose up`.

Below is an example of docker-compose file that combines a MySQL database service with a Python flask app that implements a simple login and logout web application.

```yml
version: '3'
services:
  web:
    # Path to dockerfile.
    # '.' represents the current directory in which
    # docker-compose.yml is present.
    build: .

    # Mapping of container port to host
    
    ports:
      - "5000:5000"
    # Mount volume 
    volumes:
      - "/usercode/:/code"

    # Link database container to app container 
    # for reachability.
    links:
      - "database:backenddb"
    depends_on:
      - database
    
  database:

    # image to fetch from docker hub
    image: mysql/mysql-server:5.7

    # Environment variables for startup script
    # container will use these variables
    # to start the container with these define variables. 
    environment:
      - "MYSQL_ROOT_PASSWORD=root"
      - "MYSQL_USER=testuser"
      - "MYSQL_PASSWORD=admin123"
      - "MYSQL_DATABASE=backend"
    # Mount init.sql file to automatically run 
    # and create tables for us.
    # everything in docker-entrypoint-initdb.d folder
    # is executed as soon as container is up nd running.
    volumes:
      - "/usercode/db/init.sql:/docker-entrypoint-initdb.d/init.sql" 
```

The Compose file is in YAML format. You can validate YAML file using any of the online validator tools. [Here is one for reference](https://yamlchecker.com).

- **version '3'**: Specific version of the Compile file. Compose versions are backward compatible, hence it is recommended to use the latest version.
- **services**: The services section defines all the docker images required and need to be built for the application to work. In short, it’s the collection of all different components of the application that are dependent on each other. `web` and `database` are the services in the exampe above.
- **web**: The name `web` is the name of the Flask app service. It can be anything. Docker Compose will create containers with this name.
- **build**: This clause specifies the Dockerfile location. ‘`.`’ represents the current directory where the `docker-compose.yml` file is located and `Dockerfile` is used to build an image and run the container from it. We can also provide the absolute path to `Dockerfile` instead of the current working directory symbol.
- **ports**: The `ports` clause is used to map the container ports to the host machine’s port. It creates a tunnel from the specified container port to the provided host machine’s port. This is the same as using the `-p 5000:5000` option to map the container’s 5000 port to the host machine’s 5000 port while running the container using the `docker run` command.
- **volumes**: This is the same as the `-v` option used to mount disks in `docker run` command. Here, we are attaching our code files directory to the container’s `/code` directory so that we don’t have to rebuild the images for every change in the files. This will also help in auto-reloading the server when running in debug mode.
- **links**: Links literally link one service to another. In the bridge network, we have to specify which container should be accessible to what container using a link to the respective containers. Here, we are linking the database container to the web container, so that our web container can reach the database in the bridge network.
- **image**: If we don’t have a Dockerfile and want to run a service directly using an already built docker image, then specify the image location using the ‘image’ clause. Compose will pull the image and fork a container from it.
- **environment**: Any environment variable that should be present in the container can be created using the `environment` clause. This does the same work as the `-e` argument in the `docker run` command while running a container.
- **depends_on**: This is used to inform docker-compose about all the dependencies of a service. Docker-compose will then start dependencies first and the main service after.

## Multipe Dockerfile use cases

There might be a situation where we need to have multiple Dockerfiles for different services. Examples could be:

- Creating a microservices app
- Dockerfile for different environments such as development, production

In these cases, you have to tell docker-compose the Dockerfile it should consider for creating that specific service.

> By default, docker-compose looks for a file named Dockerfile. Dockerfiles can have any name. It’s just a file without any extension. We can override the default behaviour using `dockerfile:'custom-name'` directive inside the build section.

## Accessing environment variables

### Using the `.env` file

A file with only the `.env` extension is used in the production systems to store all the variables. Since it is a hidden file, it is not normally pushed to the code.

Create a `.env` file and move the variables to the .env file. To access the variables, use `${}` syntax in docker-compose.

> The `.env` file should be in the same folder where the `docker-compose.yml` file is located.

### Using the `env_file`

Another method is to use the `env_file` keyword instead of the environment keyword in `docker-compose.yml`. In this method, it is not necessary that the `.env` file should be located in the same directory as the docker-compose file.

You will provide the location of the `.env` file like so:

```yml
env_file:
  - ./.env
```

### Priority of `.env` variables

There are multiple ways we can use the environment variables in Docker, which includes,

- `ENV` in Dockerfile
- `environment` keyword in the `docker-compose.yml` file
- `-e` option from the command line

So, docker-compose has precedence levels to overcome the clash of variables. When you set the same environment variable in multiple files, here’s the priority used by Compose to choose which value to use:

- Compose file
- Shell environment variables
- Environment file
- Dockerfile
- Variable is not defined

So, if we define a variable in the Compose file in the `- environment` section and also in a `.env` file, Compose will consider the variable declared in the Compose file from `- environment` section.

Sometimes, this might be the solution to any unexpected behavior of the application when multiple environment variables are used.
