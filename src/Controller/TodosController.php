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
        if (! $this->todos->findOne(['label' => $payload['label']])) {
            $this->todos->insert($payload);
            return JsonResponse::create($payload);
        }
        return JsonResponse::create(['error' => 'Duplicate todo found'], 400);
    }
}
