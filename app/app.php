<?php
use Silex\Application;

$app = new Application();
$app['debug'] = true;

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

$app['todos.controller'] = $app->share(function () use ($app) {
    $collection = $app['todos-collection'];
    $twig = $app['twig'];
    return new Brianium\Todos\Controller\TodosController($collection, $twig);
});

$app->get('/', 'todos.controller:index');
$app->post('/todos', 'todos.controller:create');
$app->put('/todos/{id}', 'todos.controller:edit');

return $app;
