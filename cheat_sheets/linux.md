# Linux Commands

## Basic Commands

```sh
sudo              # superuser privileges
cd <dir>          # change directory
cd ..             # go up a directory
mkdir <dir>       # make directory
ls                # list files
pwd               # show current directory
whoami            # show username
date              # show system date
mv <file> <dir>   # move <file> to <dir>, <dir> is folder name
mv <dir> <dir>    # move <dir> to <dir>
mv <file> <name>  # rename file to <name>
rm <file>         # remove file
rm -rf <dir>      # remove folder, subfolder, and files
rmdir <dir>       # remove a directory
clear             # clears the terminal
history           # prints a list of all past commands
nano <file>       # open file or create file if not exist
lsof              # list open files
host              # DNS lookup utility
```

## Nano Commands

```sh
Ctrl-R            # Read file
Ctrl-O            # Save file
Ctrl-X            # Close file
```

## Text Manipulation

### [`grep`](https://man7.org/linux/man-pages/man1/grep.1.html) command

`grep` command searches for patterns in each file.

```sh
grep [OPTION...] PATTERNS [FILE...]
```

### [`sed`](https://www.gnu.org/software/sed/manual/sed.html) command

`sed` is a stream editor. A stream editor is used to perform basic text transformations on an input stream (a file or input from a pipeline).

```sh
sed SCRIPT INPUTFILE...
```

### [`cat`](https://linuxize.com/post/linux-cat-command/) command

`cat` command either concatenates or displays files.

```sh
cat [OPTIONS] [FILE_NAMES]
```

## Additional Info

### Find Authorative Name Server

```sh
host -t ns google.com
```

- `host` - invokes the host command
- `-t` - type flag. It is used to specify the type of command.
  - Link: <https://linux.die.net/man/1/host>
- `ns` - specifies the type. It stands for the name server in this case
- `hostname.com` - can be any website

### Check Local DNS Server

```sh
cat /etc/resolv.conf
```

### I/O Redirection

```sh
# redirect output to another place
cat foo.txt > output.txt
```

File description is nothing more than a positive integer that represents an open file:

- `1` for `stdout`
- `2` for `stderr`

```sh
# redirect output (explicitly) to another place
cat foo.txt 1> output.txt

# redirect error to another place
cat nop.txt 2> error.txt
```

Note that `&1` is use to refer the value of file description `1` (`stdout`). So, `2>&1` means "redirect `stderr` to the same place when redirecting the `stdout`. So the above can also be written as:

```sh
cat foo.txt > output.txt 2>&1
```

Note also that `>` means **overwrite** and `>>` means **appends**.

#### Output to File (removed Unix colors)

```sh
<some command here> | sed "s/\x1b\[[0-9;]*m//g" > output.txt
```

#### The `<<` (here-document) operator

A command with `<<` operator will do the following:

- Launch the program specified in the left of the operator
- Grap user input, including newlines, until what is specified on the right of the operator is met on one line, `EOF` for instance
- Send all that have been read except the `EOF` value to the standard input of the program on the left

For example:

```sh
$ cat > test.sh << EOF
> Hello
> World
> EOF

$ cat test.sh
Hello
World
```
