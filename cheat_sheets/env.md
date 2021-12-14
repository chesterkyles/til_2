# Environment Variable

An **environment variable** is a variable whose value is set outside the program, typically through functionality built into the operating system or microservice. An environment variable is made up of a name/value pair, and any number may be created and available for reference at a point in time.

## Access environment variabe

Technology | Access
---------- | ------
Linux shell | `$name`
.NET Core | `.AddEnvironmentVariable();`
Java | `System.getenv("name")`
Node.JS | `process.env.name`
PHP | `.$_ENV["name"]`
Python | `os.environ.get('name')`
