<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/Helpers/ebcwallet.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

$app->withEloquent();

$app->configure('filesystems');
$app->configure('bitcoiners');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton('filesystem', function ($app) {
    return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
});

$app->singleton('bitcoiner', function ($app) {
    return $app->loadComponent('bitcoiners', 'Hht\Bitcoin\ServiceProvider', 'bitcoiner');
});

$app->singleton('path.config', function ($app) {
	return __DIR__.'/../config';
});

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->middleware([
	App\Http\Middleware\CorsMiddleware::class
]);

$app->routeMiddleware([
	'auth.lang' => App\Http\Middleware\AuthLang::class,
	'auth.version' => App\Http\Middleware\AuthVersion::class,
	'auth.user' => App\Http\Middleware\AuthUser::class,
	'auth.sign' => App\Http\Middleware\AuthSign::class,
	'auth.login' => App\Http\Middleware\AuthLogin::class,
	'auth.activate' => App\Http\Middleware\AuthActivate::class,
	'auth.daycount' => App\Http\Middleware\AuthDayCount::class,
	'tree.antitheft' => App\Http\Middleware\AntiTheft::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);

$app->register(Hht\Bitcoin\ServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
