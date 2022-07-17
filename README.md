# Artisan-Http

Here is where all API routes related to Artisan commands are generated and can be accessible via external resources such browsers. All generated routes are imported dynamically to the Laravel Application lifecycle, so as a developer you do not need to add manually. You are also encouraged to disable Artisan APIs to prevent malicious accesses.

_API routes and endpoint are imported dynamically to the application; Therefore there will not be any explicit routes that should be prevented from malicous accesses._

If route generation is enabled, you can call APIs sticked to your Artisan commands.
However, this mechanism may slow down the performance runtime, it is worth to cut off the curious user's hand.

Artisan commands are translated to routes based on their usage, parameters and options.
A common Artisan command like `php artisan make:model User -c -f` is translated to:

```
https://domain.com/artisan/api/make/model/User?options=c,f&force=true
```

So API convention is:
```
https://domain.com/artisan/api/{command}/{sub-command}/{argument}?options={options}&force=true
```

You are also free to change your API route prefix in `config/artisan.php` file:
```php
<?php

...
    'api-prefix' => 'your/desired/prefix/artisan-api'
...
```
or occasionally you may want to change it in `ArtisanApiServiceProvider.php`:
```php
<?php

...
public function handle()
{
    $this->apiPrefix('your/desired/prefix/artisan-api');
}
...
```

## TODO
- Convert dealing with arguments in query string to arrays:
  - from /artisan/api/SomeCommand?args=arg1:value1,arg2:value2,...
  - to /artisan/api/SomeCommand?arg[arg1]=value1,$arg[arg2]=value2,...
- Implement a way to deal with interactive commands like `tinker`
- Make response more readable for users, (remove "\n", ...)