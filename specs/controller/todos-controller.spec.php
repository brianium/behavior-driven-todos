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
        });

        it('should insert a todo', function () {
            $request = Request::create('/todos', 'POST', [], [], [], [], json_encode($this->data));

            $this->controller->create($request);

            $this->collection->insert($this->data)->shouldBeCalled();
            $this->getProphet()->checkPredictions();
        });
    });
});
