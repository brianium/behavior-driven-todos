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

$app['todos.controller'] = $app->share(function () use ($app) {
    $collection = $app['mongo-client']->brianium->todos;
    $twig = $app['twig'];
    return new Brianium\Todos\Controller\TodosController($collection, $twig);
});

$app->get('/', 'todos.controller:index');
$app->post('/todos', 'todos.controller:create');
$app->put('/todos/{id}', 'todos.controller:edit');

return $app;
