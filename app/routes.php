<?php
/**
 * @var $app Silex\Application
 */

use Brianium\Todos\Middleware\UniqueTodo;

$app->get('/', 'todos.controller:index');
$app->post('/todos', 'todos.controller:create')->before(new UniqueTodo());

$app->put('/todos/{todo}', 'todos.controller:edit')
    ->before(new UniqueTodo())
    ->convert('todo', 'todo-converter:convert');

$app->delete('/todos/{todo}', 'todos.controller:delete')->convert('todo', 'todo-converter:convert');
