# Introduction to Git

## Source Control

If you’re not familiar with source control, it solves a simple problem: how do you and your colleagues keep track of changes in your codebase?

A source control tool is a system that helps manage that complexity.

It’s a database of files and the histories of their states. Like a database, you have to learn the necessary skills to work on it and to get the full benefit.

## Traditional Source Control

Traditional source control tools, such as CVS (Concurrent Version System) and SVN (Subversion), had a centralized architecture. You communicated with a server that maintained the state of the source code. This could mean several things:

- The source control database could get very big
- The history could get very messy
- Managing your checkouts of code could get complicated and painful

In the old world, if you _checked out source code_, then that was a copy of some code that was inferior in status to the centralized version.

## Git

**Git**, by contrast, is fundamentally distributed. Each Git repository is a full copy of each Git repository it is copied from. It is not a “link” to a server or a “shadow” copy of another repository. You can make reference to the origin repository, but you do not have to do that. All code databases (in Git, CVS, or SVN) are known as repositories.

Git was created so that people could work on the Linux kernel across the globe and offline. So there is no concept of a central server that holds the “golden” source. Instead, people maintain their own source code database (i.e., their own repository) and reconcile, copy from, and integrate with other repositories.

**Linus Torvalds** (the creator of Git and Linux) likes to joke that he has made the Internet his backup system.

## Github

Most people use GitHub as their _reference_ or _upstream_ repository (i.e., the “primary” one), but it could easily be used as a _secondary_ or _downstream_ repository for a workflow (like personal private repositories).

GitHub’s de facto status as a centralized repository (and all the machinery that assumes its existence and continuous uptime) is the reason every GitHub outage causes a flurry of smart-alec comments about Git supposedly being a decentralized source control tool that relies on one central system.

## How Git Differes from Other Version Control System (VSCs)

- History is more malleable.
- Branching is cheap. In Git, it is an `O(1)`. `O(1)` notation means that branching takes the same amount of time, regardless of the size of the repository that you’re doing the branching in.
- Commits are made across the whole project
- No version numbers

## Initialize git repository

Run the following command within root folder of the source that you want to manage. This locally initializes a database in the folder `.git`.

```sh
git init
```

The `.git` folder contains:

- the `HEAD` file
- the `config` file

### `HEAD` file

The `HEAD` file is key: it points to the current branch or commit ID you are currently on within your Git repository. It contains a string and can be viewed using:

```sh
$ cat HEAD

ref: refs/heads/main
```

### Git configuration

The `config` file stores information about your repository’s local configuration. For example, the branches and remote repositories your repository is aware of. Again, it’s a plain text file with a basic config structure:

```sh
$ cat config

[core]
    repositoryformatversion = 0
    filemode = false
    bare = false
    logallrefupdates = true
    symlinks = false
[remote "origin"]
    url = https://github.com/chestercolita/til.git
    fetch = +refs/heads/*:refs/remotes/origin/*
[branch "main"]
    remote = origin
    merge = refs/heads/main
[submodule "learning_path/Laravel-Roadmap-Learning-Path"]
    url = https://github.com/LaravelDaily/Laravel-Roadmap-Learning-Path
    active = true
```

Refer to [git.md](../cheat_sheets/git.md) for more commands.
