<?php
/**
 * @var $app Silex\Application
 */

use Brianium\Todos\Converter\TodoConverter;

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views'
]);

$app['mongo-client'] = $app->share(function () {
    return new MongoClient();
});

$app['todos-collection'] = $app->share(function () use ($app) {
    return $app['mongo-client']->brianium->todos;
});

$app['todo-converter'] = $app->share(function () use ($app) {
    return new TodoConverter($app['todos-collection']);
});
