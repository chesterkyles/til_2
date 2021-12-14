# Docker Swarm Command

Command | Options | Explanation
------- | ------- | -----------
`docker swarm init` | | Makes the current machine a swarm node
`docker swarm leave` | `-f` | Removes the current node from a swarm cluster. The `-f` is used to force a manager node to leave a swarm cluster
`docker service create <image name>` | `-p`, `--env_file`, `--name`, `mount`  |Creates a service from an image. `-p` is used to map a host port to a service’s tasks ports.
`docker network create <name>` | `--driver` | Creates a new network. --driver specifies which driver to use for the network
`docker service update [Options] <service ID>` | `--network-add` | Updates existing service. You can attach a new network using the `--network-add` option.
`docker service [Command] --help` |  | A quick documentation of every command
`docker service ls` |  | Lists all the services running on the current node
`docker service scale <service Id>=<replica number>` |  | Scales services up and down using the replica numbers
`docker stack deploy <stack name>` | `--compose-file` | Deploys a new stack of services using a compose file. `--compose-file` specifies the compose-file to be used.
`docker stack ls` |  | Provides information about the current stack
`docker stack ps <stack_name>` |  | Lists all the tasks or containers of a stack
`docker stack services <stack name>` |  | Lists all the services in the stack
`docker stack rm <stack name>` |  | Removes specified stack from swarm
