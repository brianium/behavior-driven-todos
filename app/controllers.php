<?php
/**
 * @var $app Silex\Application
 */

use Symfony\Component\HttpFoundation\JsonResponse;

$app['todos.controller'] = $app->share(function () use ($app) {
    $collection = $app['todos-collection'];
    $twig = $app['twig'];
    return new Brianium\Todos\Controller\TodosController($collection, $twig);
});

$app->error(function (\Exception $e, $code) {
    return JsonResponse::create(['error' => $e->getMessage()], $code);
});
