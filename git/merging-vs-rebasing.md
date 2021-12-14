# Merging vs Rebasing

The `git rebase` command has a reputation for being magical Git voodoo that beginners should stay away from, but it can actually make life much easier for a development team when used with care. Comparison of `git rebase` with the related `git merge` command is explained below.

## Conceptual Overview

The first thing to understand about `git rebase` is that it **solves the same problem** as `git merge`. Both of these commands are designed to integrate changes from one branch into another branch — they just do it in very different ways.

Consider what happens when you start working on a new feature in a dedicated branch, then another team member updates the `main` branch with new commits. This results in a forked history:

<p align="center"><img src="resources/01_forked_commit_history.svg" width="450px"/></p>

Now, let’s say that the new commits in `main` are relevant to the feature that you’re working on. To incorporate the new commits into your feature branch, you have two options: **merging** or **rebasing**.

## The Merge Option

The easiest option is to merge the `main` branch into the feature branch using something like the following:

```sh
git checkout feature
git merge main

# or
git merge feature main
```

This creates a new “merge commit” in the feature branch that ties together the histories of both branches, giving you a branch structure that looks like this:

<p align="center"><img src="resources/02_merge_main_to_feature.svg" width="450px"/></p>

Merging is nice because it’s a _non-destructive_ operation. The existing branches are not changed in any way. This avoids all of the potential pitfalls of rebasing.

On the other hand, this also means that the `feature` branch will have an extraneous merge commit every time you need to incorporate upstream changes.

If `main` is very active, this can pollute your feature branch’s history quite a bit. While it’s possible to mitigate this issue with advanced `git log` options, it can make it hard for other developers to understand the history of the project.

## The Rebase Option

As an alternative to merging, you can rebase the `feature` branch onto `main` branch using the following commands:

```sh
git checkout feature
git rebase main
```

This moves the entire `feature` branch to begin on the tip of the `main` branch, effectively incorporating all of the new commits in `main`. But, instead of using a merge commit, rebasing _re-writes_ the project history by creating brand new commits for each commit in the original branch.

<p align="center"><img src="resources/03_rebase_feature_to_main.svg" width="450px"/></p>

The major benefit of rebasing is that you get a much cleaner project history.

- It eliminates the unnecessary merge commits required by `git merge`.
- It results in a perfectly linear project history as shown in above diagram. You can follow the tip of `feature` all the way to the beginning of the project without any forks. This makes it easier to navigate your project with commands like `git log`, `git bisect`, and `gitk`.

But, there are two trade-offs for this pristine commit history: **safety** and **traceability**. If you don’t follow the [**Golden Rule of Rebasing**](#the-golden-rule-of-rebasing), re-writing project history can be potentially catastrophic for your collaboration workflow.

And, less importantly, rebasing loses the context provided by a merge commit — you can’t see when upstream changes were incorporated into the feature.

## Interactive Rebasing

**Interactive rebasing** gives you the opportunity to alter commits as they are moved to the new branch. This is even more powerful than an automated rebase, since it offers complete control over the branch’s commit history.

Typically, this is used to clean up a messy history before merging a feature branch into `main`.

```sh
git checkout feature
git rebase -i main
```

This will open a text editor listing all of the commits that are about to be moved:

```txt
pick 33d5b7a Message for commit #1
pick 9480b3d Message for commit #2
pick 5c67e61 Message for commit #3
```

This listing defines exactly what the branch will look like after the rebase is performed. By changing the `pick` command and/or re-ordering the entries, you can make the branch’s history look like whatever you want.

For example, if the 2nd commit fixes a small problem in the 1st commit, you can condense them into a single commit with the `fixup` command:

When you save and close the file, Git will perform the rebase according to your instructions, resulting in project history that looks like the following:

<p align="center"><img src="resources/04_squash_commit_with_interactive.svg" width="450px"/></p>

Eliminating insignificant commits like this makes your feature’s history much easier to understand. This is something that `git merge` simply cannot do.

## The Golden Rule of Rebasing

The golden rule of `git rebase` is to **never use it on public branches**.

For example, think about what would happen if you rebased `main` onto your `feature` branch:

<p align="center"><img src="resources/05_rebase_main_branch.svg" width="450px"/></p>

The **rebase** moves all of the commits in `main` onto the tip of `feature`. The problem is that this only happened in your repository. All of the other developers are still working with the **original** `main`. Since rebasing results in brand new commits, Git will think that your `main` branch’s history has diverged from everybody else’s.

The only way to synchronize the two main branches is to merge them back together, resulting in an extra merge commit and two sets of commits that contain the same changes (the original ones, and the ones from your rebased branch). Needless to say, this is a very confusing situation.

### Force-Pushing

If you try to push the rebased `main` branch back to a remote repository, Git will prevent you from doing so because it conflicts with the **remote** `main` branch. But, you can force the push to go through by passing the `--force` flag, like so:

```sh
# Be very careful with this command!
git push --force
```

This overwrites the remote main branch to match the rebased one from your repository and makes things very confusing for the rest of your team. So, be very careful to use this command only when you know exactly what you’re doing.

## Workflow Walkthrough

Rebasing can be incorporated into your existing Git workflow as much or as little as your team is comfortable with. In this section, we’ll take a look at the benefits that rebasing can offer at the various stages of a feature’s development.

You can [read more here](https://www.atlassian.com/git/tutorials/merging-vs-rebasing#workflow-walkthrough) about incorporating rebasing into your workflow.

## Summary

If you would prefer a clean, linear history free of unnecessary merge commits, you should reach for `git rebase` instead of `git merge` when integrating changes from another branch.

On the other hand, if you want to preserve the complete history of your project and avoid the risk of re-writing public commits, you can stick with `git merge`. Either option is perfectly valid, but at least now you have the option of leveraging the benefits of `git rebase`.
