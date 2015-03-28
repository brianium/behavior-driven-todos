<?php
use Symfony\Component\HttpFoundation\Request;

describe('TodosController', function () {

    beforeEach(function () {
        $this->controller = new TodosController();
    });

    describe('->create()', function () {

        it('should insert a todo', function () {
            $request = Request::create('/todos', 'POST', [], [], [], [], json_encode([
                'label' => 'Get groceries'
            ]));

            $this->controller->create($request);
            // what is our behavior?
        });
    });
});
