<?php
namespace Brianium\Todos\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * Middleware that ensures a todo is unique
 */
class UniqueTodo
{
    /**
     * @param Request $request
     * @param Application $app
     */
    public function __invoke(Request $request, Application $app)
    {
        $todos = $app['todos-collection'];
        $payload = json_decode($request->getContent(), true);

        if (isset($payload['label']) && $todos->findOne(['label' => $payload['label']])) {
            $app->abort(422, 'Duplicate todo found');
        }
    }
}
