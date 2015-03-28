<?php
namespace Brianium\Todos\Controller;

use MongoCollection;
use Symfony\Component\HttpFoundation\Request;

class TodosController
{
    /**
     * @var MongoCollection
     */
    private $todos;

    /**
     * @param MongoCollection $collection
     */
    public function __construct(MongoCollection $todos)
    {
        $this->todos = $todos;
    }

    /**
     * Create a new todo from the request
     *
     * @param Request $request
     */
    public function create(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $this->todos->insert($payload);
    }
}
