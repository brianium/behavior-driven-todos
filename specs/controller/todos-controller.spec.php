<?php
use Symfony\Component\HttpFoundation\Request;
use Brianium\Todos\Controller\TodosController;

describe('TodosController', function () {

    beforeEach(function () {
        $this->collection = $this->getProphet()->prophesize('MongoCollection');
        $this->controller = new TodosController($this->collection->reveal());
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
    });
});
