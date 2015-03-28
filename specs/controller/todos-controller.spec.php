<?php
use Symfony\Component\HttpFoundation\Request;
use Brianium\Todos\Controller\TodosController;

describe('TodosController', function () {

    beforeEach(function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../app/views');
        $twig = new Twig_Environment($loader);

        $this->collection = $this->getProphet()->prophesize('MongoCollection');
        $this->controller = new TodosController($this->collection->reveal(), $twig);
    });

    describe('->index()', function () {

        beforeEach(function () {
            $iterator = new ArrayIterator([
                ['label' => 'Get groceries']
            ]);
            $this->collection->find()->willReturn($iterator);
        });

        it('should render the persisted todos', function () {
            $response = $this->controller->index();

            $content = $response->getContent();

            assert(strstr($content, 'Get groceries') !== false);
        });
    });

    describe('->create()', function () {

        beforeEach(function () {
            $this->data = ['label' => 'Get groceries'];
            $this->request = Request::create('/todos', 'POST', [], [], [], [], json_encode($this->data));
        });

        it('should insert a todo', function () {
            $this->controller->create($this->request);

            $this->collection->insert($this->data)->shouldBeCalled();
            $this->getProphet()->checkPredictions();
        });

        it('should return the new todo', function () {
            $response = $this->controller->create($this->request);

            $todo = json_decode($response->getContent());

            assert($todo->label == 'Get groceries');
        });

        it('should return an error response if the todo already exists', function () {
            $this->collection->findOne(['label' => 'Get groceries'])->willReturn(new stdClass);

            $response = $this->controller->create($this->request);

            assert($response->getStatusCode() === 400);
        });
    });
});
