# Git Submodules and Git subtree

## Git Submodules

Git submodules allow you to keep a git repository as a subdirectory of another git repository. Git submodules are simply a reference to another repository at a particular snapshot in time. Git submodules enable a Git repository  to incorporate and track version history of external code.

### What is a `git submodule`

Often a code repository will depend upon external code. This external code can be incorporated in a few different ways. **The external code can be directly copied and pasted into the main repository**. This method has the downside of losing any upstream changes to the external repository. Another method of incorporating external code is **through the use of a language's package management system like Ruby Gems or NPM**. This method has the downside of requiring installation and version management at all places the origin code is deployed. Both of these suggested incorporation methods do not enable tracking edits and changes to the external repository.

A **git submodule** is a record within a host git repository that points to a specific commit in another external repository. Submodules are very static and only track specific commits. Submodules do not track git refs or branches and are not automatically updated when the host repository is updated. When adding a submodule to a repository a new `.gitmodules` file will be created. The `.gitmodules` file contains meta data about the mapping between the submodule project's URL and local directory. If the host repository has multiple submodules, the `.gitmodules` file will have an entry for each submodule.

### When should you use a git submodule

If you need to maintain a strict version management over your external dependencies,  it can make sense to use git submodules. The following are a few best use cases for git submodules.

- When an external component or subproject is changing too fast or upcoming changes will break the API, you can lock the code to a specific commit for your own safety.
- When you have a component that isn’t updated very often and you want to track it as a vendor dependency.
- When you are delegating a piece of the project to a third party and you want to integrate their work at a specific time or release. Again this works when updates are not too frequent.

## Common commands for git submodules

### Add git submodule

The `git submodule add` is used to add a new submodule to an existing repository. The following is an example that creates an empty repo and explores git submodules.

```sh
$ mkdir git-submodule-demo
$ cd git-submodule-demo/
$ git init

Initialized empty Git repository in /Users/atlassian/git-submodule-demo/.git/
```

This sequence of commands will create a new directory `git-submodule-demo`, enter that directory, and initialize it as a new repository. Next we will add a submodule to this fresh new repo.

```sh
$ git submodule add https://bitbucket.org/jaredw/awesomelibrary
Cloning into '/Users/atlassian/git-submodule-demo/awesomelibrary'...
remote: Counting objects: 8, done.
remote: Compressing objects: 100% (6/6), done.
remote: Total 8 (delta 1), reused 0 (delta 0)
Unpacking objects: 100% (8/8), done.
```

The `git submodule add` command takes a URL parameter that points to a git repository. Here we have added the `awesomelibrary` as a submodule. Git will immediately clone the submodule. We can now review the current state of the repository using `git status`.

```sh
$ git status
On branch main

No commits yet

Changes to be committed:
  (use "git rm --cached <file>..." to unstage)

 new file:   .gitmodules
 new file:   awesomelibrary
```

There are now two new files in the repository `.gitmodules` and the `awesomelibrary` directory. Looking at the contents of `.gitmodules` shows the new submodule mapping

```sh
[submodule "awesomelibrary"]
 path = awesomelibrary
 url = https://bitbucket.org/jaredw/awesomelibrary
```

```sh
$ git add .gitmodules awesomelibrary/
$ git commit -m "added submodule"
[main (root-commit) d5002d0] added submodule
 2 files changed, 4 insertions(+)
 create mode 100644 .gitmodules
 create mode 160000 awesomelibrary
```

### Cloning git submodules

```sh
git clone /url/to/repo/with/submodules
git submodule init
git submodule update
```

### Git submodule Init

The default behavior of `git submodule init` is to copy the mapping from the `.gitmodules` file into the local `./.git/config` file. This may seem redundant and lead to questioning `git submodule init` usefulness. `git submodule init` has extend behavior in which it accepts a list of explicit module names. This enables a workflow of activating only specific submodules that are needed for work on the repository. This can be helpful if there are many submodules in a repo but they don't all need to be fetched for work you are doing.

### Submodule workflows

Once submodules are properly initialized and updated within a parent repository they can be utilized exactly like stand-alone repositories. This means that submodules have their own branches and history. When making changes to a submodule it is important to publish submodule changes and then update the parent repositories reference to the submodule. Let’s continue with the `awesomelibrary` example and make some changes:

```sh
$ cd awesomelibrary/
$ git checkout -b new_awesome
Switched to a new branch 'new_awesome'
$ echo "new awesome file" > new_awesome.txt
$ git status
On branch new_awesome
Untracked files:
  (use "git add <file>..." to include in what will be committed)

 new_awesome.txt

nothing added to commit but untracked files present (use "git add" to track)
$ git add new_awesome.txt
$ git commit -m "added new awesome textfile"
[new_awesome 0567ce8] added new awesome textfile
 1 file changed, 1 insertion(+)
 create mode 100644 new_awesome.txt
$ git branch
  main
* new_awesome
```

Here we have changed directory to the `awesomelibrary` submodule. We have created a new text file `new_awesome.txt` with some content and we have added and committed this new file to the submodule. Now let us change directories back to the parent repository and review the current state of the parent repo.

```sh
$ cd ..
$ git status
On branch main
Changes not staged for commit:
  (use "git add <file>..." to update what will be committed)
  (use "git checkout -- <file>..." to discard changes in working directory)

 modified:   awesomelibrary (new commits)

no changes added to commit (use "git add" and/or "git commit -a")
```

Executing `git status` shows us that the parent repository is aware of the new commits to the `awesomelibrary` submodule. It doesn't go into detail about the specific updates because that is the submodule repositories responsibility. The parent repository is only concerned with pinning the submodule to a commit. Now we can update the parent repository again by doing a `git add` and `git commit` on the submodule. This will put everything into a good state with the local content. If you are working in a team environment it is critical that you then `git push` the submodule updates, and the parent repository updates.

When working with submodules, a common pattern of confusion and error is forgetting to push updates for remote users. If we revisit the `awesomelibrary` work we just did, we pushed only the updates to the parent repository. Another developer would go to pull the latest parent repository and it would be pointing at a commit of `awesomelibrary` that they were unable to pull because we had forgotten to push the submodule. This would break the remote developers local repo. To avoid this failure scenario make sure to always commit and push the submodule and parent repository.

## Git Subtree

The Internet is full of articles on why you shouldn’t use Git submodules. While submodules are useful for a few use cases, they do have several drawbacks.

Are there alternatives? The answer is: yes! There are (at least) two tools that can help track the history of software dependencies in your project while allowing you to keep using Git:

- git subtree
- Google repo

### What is `git subtree`, and why should I use it

`git subtree` lets you nest one repository inside another as a sub-directory. It is one of several ways Git projects can manage project dependencies.

<p align="center"><img src="resources/git_subtree_001.png" width="450px"/></p>

Why you may want to consider `git subtree`

- Management of a simple workflow is easy.
- Older version of Git are supported (even older than v1.5.2).
- The sub-project’s code is available right after the clone of the super project is done.
- `git subtree` does not require users of your repository to learn anything new. They can ignore the fact that you are using `git subtree` to manage dependencies.
- `git subtree` does not add new metadata files like `git submodule` does (i.e., `.gitmodule`).
- Contents of the module can be modified without having a separate repository copy of the dependency somewhere else.

Drawbacks (but in our opinion they're largely acceptable):

- You must learn about a new merge strategy (i.e.`git subtree`).
- Contributing code back upstream for the sub-projects is slightly more complicated.
- The responsibility of not mixing super and sub-project code in commits lies with you.

### How to use `git subtree`

`git subtree` is available in stock version of Git since May 2012 – v1.7.11 and above. The version installed by homebrew on OSX already has subtree properly wired, but on some platforms you might need to follow the installation instructions.

Here is a canonical example of tracking a vim plug-in using `git subtree`.

#### The quick and dirty way without remote tracking

If you just want a couple of one-liners to cut and paste, read this paragraph. First add `git subtree` at a specified prefix folder:

```sh
git subtree add --prefix .vim/bundle/tpope-vim-surround https://bitbucket.org/vim-plugins-mirror/vim-surround.git main --squash
```

(The common practice is to not store the entire history of the subproject in your main repository, but If you want to preserve it just omit the `–squash` flag.)

The above command produces this output:

```sh
git fetch https://bitbucket.org/vim-plugins-mirror/vim-surround.git main
warning: no common commits
remote: Counting objects: 338, done.
remote: Compressing objects: 100% (145/145), done.
remote: Total 338 (delta 101), reused 323 (delta 89)
Receiving objects: 100% (338/338), 71.46 KiB, done.
Resolving deltas: 100% (101/101), done.
From https://bitbucket.org/vim-plugins-mirror/vim-surround.git
* branch main -} FETCH_HEAD
Added dir '.vim/bundle/tpope-vim-surround'
```

As you can see this records a merge commit by squashing the whole history of the vim-surround repository into a single one:

```sh
1bda0bd [3 minutes ago] (HEAD, stree) Merge commit 'ca1f4da9f0b93346bba9a430c889a95f75dc0a83' as '.vim/bundle/tpope-vim-surround' [Nicola Paolucci]
ca1f4da [3 minutes ago] Squashed '.vim/bundle/tpope-vim-surround/' content from commit 02199ea [Nicola Paolucci]
```

If after a while you want to update the code of the plugin from the upstream repository you can just do a `git subtree` pull:

```sh
git subtree pull --prefix .vim/bundle/tpope-vim-surround https://bitbucket.org/vim-plugins-mirror/vim-surround.git main --squash
```

This is very quick and painless, but the commands are slightly lengthy and hard to remember. We can make the commands shorter by adding the sub-project as a remote.

#### Adding the sub-project as a remote

Adding the subtree as a remote allows us to refer to it in shorter form:

```sh
git remote add -f tpope-vim-surround https://bitbucket.org/vim-plugins-mirror/vim-surround.git
```

Now we can add the subtree (as before), but now we can refer to the remote in short form:

```sh
git subtree add --prefix .vim/bundle/tpope-vim-surround tpope-vim-surround main --squash
```

The command to update the sub-project at a later date becomes:

```sh
git fetch tpope-vim-surround main
git subtree pull --prefix .vim/bundle/tpope-vim-surround tpope-vim-surround main --squash
```

#### Contributing back upstream

We can freely commit our fixes to the sub-project in our local working directory now. When it’s time to contribute back to the upstream project, we need to fork the project and add it as another remote:

```sh
git remote add durdn-vim-surround ssh://git@bitbucket.org/durdn/vim-surround.git
```

Now we can use the _subtree push_ command like the following:

```sh
git subtree push --prefix=.vim/bundle/tpope-vim-surround/ durdn-vim-surround main
git push using: durdn-vim-surround main
Counting objects: 5, done.
Delta compression using up to 4 threads.
Compressing objects: 100% (3/3), done.
Writing objects: 100% (3/3), 308 bytes, done.
Total 3 (delta 2), reused 0 (delta 0)
To ssh://git@bitbucket.org/durdn/vim-surround.git
02199ea..dcacd4b dcacd4b21fe51c9b5824370b3b224c440b3470cb -} main
```

After this we’re ready and we can open a pull-request to the maintainer of the package.

### Can I do this without using the git subtree command

Yes! Yes you can. `git subtree` is different from the subtree merge strategy. You can still use the merge strategy even if for some reason `git subtree` is not available. Here is how you would go about it.

Add the dependency as a simple git remote:

```sh
git remote add -f tpope-vim-surround https://bitbucket.org/vim-plugins-mirror/vim-surround.git
```

Before reading the contents of the dependency into the repository, it’s important to record a merge so that we can track the entire tree history of the plug-in up to this point:

```sh
git merge -s ours --no-commit tpope-vim-surround/main
```

Which outputs:

```sh
Automatic merge went well; stopped before committing as requested
```

We then read the content of the latest tree-object into the plugin repository into our working directory ready to be committed:

```sh
git read-tree --prefix=.vim/bundle/tpope-vim-surround/ -u tpope-vim-surround/main
```

Now we can commit (and it will be a merge commit that will preserve the history of the tree we read):

```sh
git ci -m"[subtree] adding tpope-vim-surround"
[stree 779b094] [subtree] adding tpope-vim-surround
```

When we want to update the project we can now pull using the git subtree merge strategy:

```sh
git pull -s subtree tpope-vim-surround main
```

### `Git subtree` is a great alternative

After having used git submodules for a while, you'll see `git subtree` solves lots of the problems with `git submodule`. As usual, with all things Git, there is a learning curve to make the most of the feature.
