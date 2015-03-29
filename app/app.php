<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Brianium\Todos\Middleware\UniqueTodo;

$app = new Application();
$app['debug'] = true;

// Services
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

// Controllers
$app['todos.controller'] = $app->share(function () use ($app) {
    $collection = $app['todos-collection'];
    $twig = $app['twig'];
    return new Brianium\Todos\Controller\TodosController($collection, $twig);
});

$app->error(function (\Exception $e, $code) {
    return JsonResponse::create(['error' => $e->getMessage()], $code);
});

// Routes
$app->get('/', 'todos.controller:index');
$app->post('/todos', 'todos.controller:create')->before(new UniqueTodo());
$app->put('/todos/{id}', 'todos.controller:edit')->before(new UniqueTodo());

return $app;
