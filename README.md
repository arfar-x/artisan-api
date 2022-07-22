<span style="text-align: center">
  <h1 style="color: #2e2e2e">Artisan Api</h1>
</span>

---

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/aariow/artisan-api) ![GitHub](https://img.shields.io/github/license/aariow/artisan-api) ![Packagist Version](https://img.shields.io/packagist/v/aariow/artisan-api?label=version) ![GitHub branch checks state](https://img.shields.io/github/checks-status/aariow/artisan-api/master?label=branch)
![Packagist Downloads](https://img.shields.io/packagist/dm/aariow/artisan-api?label=Packagist%20downloads) ![GitHub all releases](https://img.shields.io/github/downloads/aariow/artisan-api/total?label=Github%20downloads) ![tests-passed-green](https://img.shields.io/badge/test-passed-green) ![GitHub repo size](https://img.shields.io/github/repo-size/aariow/artisan-api?label=size)

![Linkedin URL](https://img.shields.io/badge/Linkedin-aariow-blue?style=social&logo=linkedin&url=linkedin.com/in/aariow)

**There might be some times you wanted to execute an Artisan command, but you did not have access to shell or SSH.
Here we brought REST API solution for you.**

**You are able to run Artisan commands by REST APIs easily.**

### Table of contents
- **[Get Started](#get-started)**
- **[Endpoints](#endpoints)**
  - **[Routes](#routes)**
  - **[Responses](#responses)**
    - **[Successful](#successful)**
    - **[Not Found](#not-found)**
    - **[Invalid Arguments format](#invalid-arguments-format)**
    - **[Invalid Options format](#invalid-options-format)**
  - **[Forbidden Routes](#forbidden-routes)**
  - **[Authentication](#authentication)**
- **[Configurations](#configurations)**
  - **[API Prefix and HTTP Method](#api-prefix-and-http-method)**
  - **[Auto Run](#auto-run)**
- **[Middlewares](#middlewares)**
- **[Running Tests](#tests)**
- **[Useful tips](#tips)**
- **[Todo]($todo)**


### Get Started

To use this package, you should install it alongside [Laravel v9.5](https://laravel.com/) and [PHP v8.0](https://php.net) or higher.

you can install it via [Composer package manager](https://getcomposer.org/):
```shell
composer require aariow/artisan-api --dev
```

Although, Artisan-Api has production decetor itself, it is possible to install it globally.

### Endpoints

As its name explains, all commands are available via HTTP with _POST_ method.
All commands will be generated as routes and integrated into Laravel Routing system. You only need to send a POST request and follow the signature, like other REST API endpoints.

There are two kinds of commands; one is normal commands like `php artisan list` or `php artisan cache:clear`, and the second form is `GeneratorCommands` which tend to create files within your application like `make:model` and `make:migration`. These kind of commands have different purposes, you should follow diffenerent convention.

> **`GeneratorCommand`** are instance of **`Illuminate\Console\GeneratorCommand`** that extends **`Illuminate\Console\Command`** class.

All commands existed by default or created by you will be discovered automatically and you do not have to do anything manually. Thus, their endpoints will be generated dynamically to your application. So if you delete/add any command class, there is no reason to worry.

#### Routes

Let's dive into using endpoints:

Routes are generated with the following format:
```shell
https://domain.com/artisan/api/{command}/{subcommand}?args=key1:value1,key2:value2&options=opt1:value1,opt2
```
\
So the above endpoint will be translated to:
```shell
php artisan command:subcommand value1 value2 --opt1=value1 --opt2
```
\
And for **Generator** commands the endpoint is:
```shell
https://domain.com/artisan/api/{command}/{subcommand}/{name}?args=key1:value1,key2:value2&options=opt1:value1,opt2
```

**Pay attention that there is a `name` variable. As all Generator commands need an argument called `name`, this needs to be specified by what you desire.**

\
Command Examples:

```shell
php artisan list
```
will be translated to:
```shell
https://domain.com/artisan/api/list
```
\
and this:
```shell
php artisan cache:forget myCachedKeyName
```
will be translated to:
```shell
https://domain.com/artisan/api/cache/forget?args=key:myCachedKeyName
```
\
Another one:
```shell
php artisan optimize:clear -v
```
will be translated to:
```shell
https://domain.com/artisan/api/optimize/clear?options=v
```
\
A **Generator** one:
```shell
php artisan make:model MyModel --controller -f
```
will be translated to:
```shell
https://domain.com/artisan/api/make/model/MyModel?options=controller,f
```
<br>

> Options with more than _one_ character will be translated to `--option`.

#### Responses

After calling an endpoint, you will receive a `Json` response.

##### Successful
When everything works perfectly: `status : 200 OK`
```json
{
  ok: true,
  status: 200,
  output: "Output of the command, given by Artisan"
}
```

##### Not found
When inputed command is not found by application: `status : 404 Not Found`
```json
{
  ok: false,
  status: 404,
  output: "Command "command:subcommand" is not defined."
}
```

##### Invalid Arguments format
When arguments are given by an invalid format: `status : 500 Server Error`
```json
{
  ok: false,
  status: 500,
  output: "Argument(s) 'key:value' given by an invalid format."
}
```

##### Invalid Options format:
When options are given by an invalid format: `status : 500 Server Error`
```json
{
  ok: false,
  status: 500,
  output: "Options(s) 'key:value' given by an invalid format."
}
```

#### Forbidden routes

You might want to limit access to some critical commands like `db:seed`. **Artisan-Api** has thought about it and make those commands inaccessible by client.
To specify forbidden commands, you are encouraged to add them within `config/artisan.php` file:
```php
return [
    ...,

    'forbidden-routes' => [
        'clear-compiled',
        'tinker',
        'up',
        'down',
        'serve',
        'completion',
        '_complete',
        'db*', // all `db:seed` and `db:wipe` will be inaccessible
        '*publish' // like `vendor:publish`
    ]
];
```

Whenever client wants to access these commands by endpoints, it will be given a `404 NOT_FOUND` HTTP response.

#### Authentication
All enpoints will be generated under the `api` middleware of Laravel and prevented by built-in authnetication system, mostly with `Sanctum` and API tokens.

### Configurations

As mentioned before, there is a configuration `config/artisan.php` file.
You are free to modify specified values as you desire.

#### API Prefix and HTTP Method
Here, it is possible to change default API prefix and customize it as necessary. In addition you can access endpoints with any HTTP method as you set.

```php
return [
    ...
    'api' => [
        'prefix' => "/artisan/api",
        'method'    => 'POST', // or ['POST', 'PUT']
    ],
    ...
];
```

#### Auto Run

For some reason and mostly on production mode, you do not want to allow commands to be executed by HTTP request. To prevent this behavior, set that `auto-run` to `false`:
```php
return [
    ...
    'auto-run' => false,
    ...
];
```

**This prevents not to load package's service-provider (`ArtisanApiServiceProvider`) by default.**

### Middlewares
There are two middlewares in **Artisan-Api**.

`CheckEnvMode` middleware exists to abort requests while in production environment.

`AbortForbiddenRoute` middleware exists to throw `404 NOT_FOUND` status code while accessing to forbidden routes.

### Useful tips
;)

### Todo
1. It'd better be done to take `args` and `options`  in query string, to be array.
   - Like: `?arg[key1]=value1&arg[key2]=value2 (it is a more standard way to deal with query string values)
2. Implement a way to deal with interactive commands like `tinker` (maybe can be implemented by socket)
3. Make response more readable for users, (remove "\n", ...)