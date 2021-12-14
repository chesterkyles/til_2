# TIL

[![markdownlint](https://github.com/chestercolita/til/actions/workflows/markdownlint.yml/badge.svg)](https://github.com/chestercolita/til/actions/workflows/markdownlint.yml)

[Online Portfolio](https://chestercolita.netlify.app/)

## TIL: Today I Learned

The following are the summary of the things that I learned in a daily basis whether by reading books, articles or by trying to implement state-of-the-art technologies. Note that what I have written are just concise explanations on various technologies that I am trying to learn. Also, some of them are mere summarized version of the official documentation since the documentation is better in explaining things than me :relaxed:. The things that I listed below are based on the learnings I had while doings tasks and/or knowledge acquired from implementing/coding whenever something catches my interest.

## To-do List

- Currently Studying: Vue.js and Nuxt.js (Vue Framework)
- Update [Vue.js](#vuejs) section
- Learning Path (On-hold): [DevOps for Developers](learning_path/devops.md)
- Read: [Article reading list](learning_path/articles.md)

---

## Things I Learned

### Cheat Sheets

- [git Commands](cheat_sheets/git.md)
- [Linux Commands](cheat_sheets/linux.md)
- [docker Commands](cheat_sheets/docker.md)
- [docker-compose Commands](cheat_sheets/docker-compose.md)
- [Docker Swarm Commands](cheat_sheets/docker-swarm.md)
- [kubectl commands](cheat_sheets/kubectl.md)
- [cURL Commands](cheat_sheets/curl.md)
- [Environment Variables](cheat_sheets/env.md)
- [VSCode Keyboard Shortcuts](cheat_sheets/vscode-shortcut.md)
- [VSCode Tips and Tricks](cheat_sheets/vscode-tips.md)

### Categories

- [Codewars](#codewars)
- [Network Fundamentals](#network-fundamentals)
- [Git](#git)
- [Docker](#docker)
- [Kubernetes](#kubernetes)
- [Firebase](#firebase)
- [Laravel](#laravel)
- [Node.js](#nodejs)
- [Vue.js](#vuejs)
- [Nuxt.js](#nuxtjs)
- [BootstrapVue](#bootstrapvue)
- [HTML/CSS/JS](#htmlcssjs)
- [Github Workflow](#github-workflow)
- [Amazon Web Services (AWS)](#amazon-web-services-aws)
- [Google Cloud](#google-cloud)

---

### Labels

[![label: summary][~summary]][summary]
[![label: read][~read]][read]
[![label: update][~update]][update]

```txt
- summary - I was able to learn but have not written summary yet
- read - Topics that I want to read in the future
- update - File is in need for an update
```

---

### Codewars

- [PHP](codewars/php.md)
- [Javascript](codewars/javascript.md)

### Network Fundamentals

- [Internet and the OSI Model](network_fundamentals/internet.md)
- [Application Layer](network_fundamentals/application-layer.md)
- [Transport Layer](network_fundamentals/transport-layer.md)
- [Socket Programming with Python](network_fundamentals/socket-programming.md)
- [Network Layer](network_fundamentals/network-layer.md)
- [Internet Protocol v4](network_fundamentals/ipv4.md)
- [Data Link Layer](network_fundamentals/data-link-layer.md)
- [Cross-Origin Resource Sharing (CORS)](network_fundamentals/cors.md) [![label: read][~read]][read]
- [Load Balancers](network_fundamentals/load-balancers.md) [![label: read][~read]][read]

### Git

- [Introduction to Git](git/introduction.md)
- [Merging vs Rebasing](git/merging-vs-rebasing.md)
- [Resetting, Checking Out, and Reverting](git/reset-checkout-revert.md)
- [Git cherry-pick](git/cherry-pick.md)
- [Advanced Git log](git/advanced-log.md)
- [Git Hooks](git/git-hooks.md)
- [Refs and the Reflog](git/ref-reflog.md)
- [Git submodules and Git subtree](git/submodules-subtree.md)
- [Git LFS (large file storage)](git/large-file-storage.md) [![label: read][~read]][read]

### Docker

- [Introduction: Why Docker](docker/introduction.md)
- [Docker Notes](docker/docker-notes.md)
- [Dockerfile](docker/dockerfile-entries.md) [![label: update][~update]][update]
- [Docker-Compose](docker/docker-compose.md)
- [Docker Swarm](docker/docker-swarm.md)
- [Docker Security: Risks and Best Practices](docker/docker-security.md)
- [Networking in Compose](docker/networking-in-compose.md)
- [Dockerizing nodeJS application](docker/dockerizing-nodejs.md) [![label: summary][~summary]][summary]

### Kubernetes

- [WSL+Docker: Kubernetes on Windows](kubernetes/setup.md)
- [Monolith vs Microservice](kubernetes/monolith-vs-microservice.md) [![label: update][~update]][update]
- [Pods](kubernetes/pods.md) [![label: update][~update]][update]
- [ReplicaSets](kubernetes/replicasets.md) [![label: update][~update]][update]
- [Services](kubernetes/services.md) [![label: update][~update]][update]
- [Deployments](kubernetes/deployments.md) [![label: update][~update]][update]

### Firebase

#### Android Client App

- [Cloud Messaging using FirebaseMessaging API](firebase/android_client/messaging.md) [![label: update][~update]][update]

#### Firebase Project

- [Trigger a function on Cloud Storage changes](firebase/firebase_project/cloud_functions/cloud-storage-triggers.md) [![label: read][~read]][read] ![label: summary][~summary]

#### Admin SDK for PHP

- [Firebase For Laravel](firebase/laravel_server/firebase-for-laravel.md)
- [Create a firebase user programmatically](firebase/laravel_server/create-user.md)
- [Send a push notification to a specific device](firebase/laravel_server/cloud-messaging.md)
- [Verify ID tokens using Authentication](firebase/laravel_server/authentication.md)

### Laravel

- [Create HTTP tests](laravel/http-test.md)
- [Handling Errors and Exceptions](laravel/error-handling.md)
- [Authenticate Users](laravel/authentication.md)
- [Authorizing Actions](laravel/authorization.md)
- [Inspect and filter HTTP requests using Middleware](laravel/middleware.md)
- [HTTP Responses](laravel/http-response.md)
- [HTTP Requests](laravel/http-request.md)
- [File Storage using Laravel's filesystem](laravel/file-storage.md)
- [Mocking Objects for Testing](laravel/mocking.md)
- [Laravel Sail](laravel/sail.md)
- [Laravel Sanctum](laravel/sanctum.md) [![label: update][~update]][update]
- [Database: Migrations](laravel/migrations.md) [![label: update][~update]][update]
- [Add breadcrumbs using Tabuna/Breadcrumbs package](laravel/tabuna-breadcrumbs.md) [![label: summary][~summary]][summary]

### Javascript

- [Javascript Console Method](javascript/console.md)

### Node.js

- [Web application using Express framework](nodejs/express.md) [![label: read][~read]][read]
- [Network requests using Axios](nodejs/axios.md) [![label: read][~read]][read]
- [Use Embedded Javascript Templating (EJS) as Template Engine](node/ejs.md) [![label: read][~read]][read]
- [Optional Chaining](nodejs/optional-chaining.md)

### Vue.js

- [Application and Component Instances](vuejs/component-instance.md)
- [Component Basics](vuejs/component-basics.md) [![label: update][~update]][update]
- [Custom Directive](vuejs/custom-directive.md)
- [Lifecycle Hooks](vuejs/lifecycle-hooks.md)
- [Custom Directives](vuejs/custom-directives.md)
- [Fragments](vuejs/fragments.md)
- [Computed Properties](vuejs/computed-properties.md)

### Nuxt.js

- [File System Routing](nuxtjs/file-system-routing.md)
- [Data Fetching](nuxtjs/data-fetching.md)

### BootstrapVue

### HTML/CSS

- [Build responsive sites with Bootstrapv4](html_css/bootstrap.md) ![label: summary][~summary] [![label: update][~update]][update]

### Github Workflow

- [About Github actions](github_workflow/github-actions.md) [![label: read][~read]][read]

### Amazon Web Services (AWS)

- [AWS Cloud Development Kit](aws/cdk.md) [![label: read][~read]][read]
- [Create a CI/CD pipeline for Amazon ECS with Github Actions](aws/ecs.md) [![label: read][~read]][read]
- [Serverless Architecture](aws/serverless.md) [![label: read][~read]][read]
- [Relational Database Service (RDS)](aws/rds.md) [![label: read][~read]][read]
- [Elastic Load Balancing (ELB)](aws/elb.md) [![label: read][~read]][read]

### Google Cloud

- [Speech-to-Text API](google_cloud/speech-to-text.md) [![label: read][~read]][read]
- [Cloud Storage](google_cloud/cloud-storage.md) [![label: read][~read]][read]

---

## Note

This repository is used for solely personal purposes. Everything written here are not used nor distributed for commercial purposes. Mostly written here are mere summaries of official documentations.

## References

- Laravel - <https://laravel.com/docs/8.x>
- Advanced Git Tutorials - <https://www.atlassian.com/git/tutorials/advanced-overview>
- Github Docs -<https://docs.github.com/en/github>
- Docker Reference - <https://docs.docker.com/reference/>
- Docker Product Manuals - <https://docs.docker.com/desktop/>
- Firebase Admin SDK for PHP - <https://firebase-php.readthedocs.io/en/5.x/>
- Kubernetes - <https://kubernetes.io/docs/home/>
- Kubectl Guide - <https://kubectl.docs.kubernetes.io>
- Nuxtjs - <https://nuxtjs.org/docs/get-started/installation>
- Vuejs - <>

## Supplementary

- Online Portfolio: <https://chestercolita.netlify.app/>
- Repositories: <https://github.com/chestercolita?tab=repositories>
- Gist: <https://gist.github.com/chestercolita>
- Educative: <https://educative.io/signup?referralCode=chester-39ypR5yAKrr>

[~read]: https://img.shields.io/github/labels/chestercolita/til/read
[read]: https://github.com/chestercolita/til/labels/read
[~summary]: https://img.shields.io/github/labels/chestercolita/til/summary
[summary]: https://github.com/chestercolita/til/labels/summary
[~update]: https://img.shields.io/github/labels/chestercolita/til/update
[update]: https://github.com/chestercolita/til/labels/update
