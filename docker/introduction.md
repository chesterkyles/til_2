# Introduction: Why Docker

## Terminologies

### Container

A **container** is what we eventually want to run and host in Docker. You can think of it as an isolated machine, or a virtual machine if you prefer.

From a conceptual point of view, a _container_ runs inside the Docker host isolated from the other containers and even the host OS. It cannot see the other containers, physical storage, or get incoming connections unless you explicitly state that it can. It contains everything it needs to run: OS, packages, runtimes, files, environment variables, standard input, and output.

### Images

Any container that runs is created from an **image**. An image describes everything that is needed to create a container; it is a template for containers. You may create as many containers as needed from a single image.

### Registries

Images are stored in a **registry**. Each container lives its own life, and they both share a common root: their image from the registry.

## A DevOps Enabler Tool

Docker is an engine that runs containers. As a tool, containers allow you to solve many challenges created in the growing DevOps trend.

In DevOps, the Dev and Ops teams have conflicting goals:

Dev Team Seeks | Ops Team Seeks
-------------- | --------------
Frequent deployments and updates | Stability of production apps
Easy creation of new resources | Manage infrastructure, not applications
-| Monitoring and control

As an agile developer, We want to frequently publish our applications so that deployment becomes a routine. The rationale behind this is that this agility makes the “go-to production” event a normal, frequent, completely mastered event instead of a dreaded disaster that may awake monsters who hit me one week later. On the other hand, it is the Ops team that has to face the user if anything goes wrong in deployment - so they naturally want stability.

Containers make deployment easy. Deploying is as simple as running a new container, routing users to the new one, and trashing the old one. It can even be automated by orchestration tools. Since it’s so easy, we can afford to have many containers serving a single application for increased stability during updates.

If you don’t use containers, Ops need to handle your hosting environment: runtimes, libraries, and OS needed by your application. On the other hand, when using containers, they need one single methodology that can handle the containers you provide no matter what’s inside them. You may as well use .NET Core, Java, Node.JS, PHP, Python, or another development tool: it doesn’t matter to them as long as your code is containerized. This is a considerable advantage for containers when it comes to DevOps.

## Solves Dependency Conficts

Without containers, the dependencies and files are all placed together on a server. Since managing these dependencies is time-consuming, similar apps are typically grouped on the same server, sharing their dependencies.

Now suppose you want to upgrade the PHP runtime from version 5.6 to 7.2. However, the version change induces breaking changes in the applications that therefore need to be updated. You need to update both App 1 and App 2 when proceeding with the upgrade. On a server that may host many apps of this type, this is going to be a daunting task, and you’ll need to delay the upgrade until all apps are ready.

Another similar problem is when you want to host App 3 on the same server, but App 3 uses the Node.JS runtime together with a package that, when installed, changes a dependency used by the PHP runtime. Conflicts between runtimes happen often, so you’ve probably faced that problem already.

Containers solve this problem since each app will run inside its own container with its own dependencies.

## Allow Easy Scaling Up

When a server application needs to handle a higher usage than what a single server can handle, the solution is well-known, place a reverse proxy in front of it, and duplicate the server as many times as needed.

That is only going to make things worse when upgrading: we’ll need to upgrade each server’s dependencies together with all of the conflicts that may induce.

Again, containers have a solution for this. Containers are based on images. You can run as many containers as you wish from a single image — all the containers will support the exact same dependencies.

Better yet: when using an orchestrator, you merely need to state how many containers you want and the image name and the orchestrator creates that many containers on all of your Docker servers.

## Allow Seamless Upgrades

Even in scaled-up scenarios, a container-based approach makes tricky concepts seem trivial. Without containers, your favorite admin will not be happy with you if he has to update every server, including the dependencies.

Of course, in such a case, the update process depends on the application and its dependencies. Don’t even try to tell your admins about DevOps if you want to remain alive.

By using containers, it’s a simple matter of telling the orchestrator that you want to run a new image version, and it gradually replaces every container with another one running the new version. Whatever technology stack is running inside the containers, telling the orchestrator that you want to run a new image version is a simple command (in fact, by changing just one line).

## International Commerce Already Uses Containers

Sure, you may wonder why is there such a comparison.  International commerce faced this same delivery problem; we are trying to deliver software as fluently as possible, and commerce needs to deliver goods as fluently as possible.

The ship remained docked at the pier for a few days while each good was being loaded onto it. Goods had varying sizes and handling precautions, and ships had storage of varying types and sizes. That’s what made loading and unloading a slow process. Slow and costly; it required many people to do it, plus the immobilization of the ship and goods has a cost.

A solution was found: containers. The idea is straightforward; use boxes of a standard size and fill them with whatever you want. You now only need to handle standardized boxes no matter what they contain. Problem solved; the ship can now be tailored to host many containers in a way that allows for fast (un)loading thanks to standardized tools like cranes:

In fact, the whole transport chain (trains, trucks, etc.) can be tailored to manage containers efficiently. Believe it or not, Docker containers are very similar. When you create an image, you stuff your software into a container image. When a machine runs that image, a container is created. Container images and containers can be managed in a standardized way, which allows for standard solutions during a containerized software’s lifecycle:

- common build chain
- common image storage
- common way to deploy and scale-up
- common hosting of running containers
- common control and monitoring of running containers
- common ways to update running containers to a new version

The most important part is, that whatever the software inside the container is, it can be handled in a standardized way.
