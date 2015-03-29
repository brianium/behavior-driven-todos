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

            $this->collection->insert(array_merge($this->data, ['done' => false]))->shouldBeCalled();
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

    describe('->edit()', function () {

        beforeEach(function () {
            $this->data = ['label' => 'Get groceries', 'done' => true];
            $this->request = Request::create('/todos', 'PUT', [], [], [], [], json_encode($this->data));
        });

        $edit = function() {
            $todo = new stdClass;
            $todo->label = 'Do things';
            $mongoId = new MongoId();
            $this->collection->findOne(['_id' => $mongoId])->willReturn($todo);
            $this->collection->save($todo)->shouldBeCalled();

            return $this->controller->edit((string) $mongoId, $this->request);
        };

        it('should modify an existing todo', function () use ($edit) {
            $this->collection->findOne(['label' => 'Get groceries'])->willReturn(null);

            $edit();

            $this->getProphet()->checkPredictions();
        });

        it('should return the modified todo', function () use ($edit) {
            $this->collection->findOne(['label' => 'Get groceries'])->willReturn(null);

            $response = $edit();
            $todo = json_decode($response->getContent());

            assert($todo->label === 'Get groceries', 'incorrect label');
            assert($todo->done, 'todo is not done');
        });

        it('should return an error response if the todo already exists', function () use ($edit) {
            $this->collection->findOne(['label' => 'Get groceries'])->willReturn(new stdClass);

            $response = $edit();

            assert($response->getStatusCode() === 400);
        });

        it('should return a not found response if the todo does not exist', function () {
            $mongoId = new MongoId();
            $this->collection->findOne(['_id' => $mongoId])->willReturn(null);

            $response = $this->controller->edit((string) $mongoId, $this->request);

            assert($response->getStatusCode() === 404);
        });

    });
});
