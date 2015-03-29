<?php
namespace Brianium\Todos\Controller;

use MongoCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class TodosController
{
    /**
     * @var MongoCollection
     */
    private $todos;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param MongoCollection $collection
     */
    public function __construct(MongoCollection $todos, Twig_Environment $twig)
    {
        $this->todos = $todos;
        $this->twig = $twig;
    }

    /**
     * Render the persisted todos
     *
     * @return Response
     */
    public function index()
    {
        $content = $this->twig->render('index.twig', ['todos' => $this->todos->find()]);
        return Response::create($content);
    }

    /**
     * Create a new todo from the request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $payload['done'] = false;
        $this->todos->insert($payload);
        return JsonResponse::create($payload);
    }

    /**
     * Edit an existing todo
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function edit($id, Request $request)
    {
        $todo = $this->todos->findOne(['_id' => new \MongoId($id)]);

        if ($todo === null) {
            return JsonResponse::create(['error' => 'Todo not found'], 404);
        }

        $payload = json_decode($request->getContent(), true);
        $todo = array_merge($todo, $payload);
        $this->todos->save($todo);
        return JsonResponse::create($todo);
    }

    /**
     * Delete an existing todo
     *
     * @param array $todo
     * @return Response
     */
    public function delete(array $todo)
    {
        $this->todos->remove($todo);
        return Response::create('', 204);
    }
}
