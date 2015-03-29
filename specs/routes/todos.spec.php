<?php
describe('/todos/{id}', function () {

    beforeEach(function () {
        $app = $this->getHttpKernelApplication();
        $app['todos-collection']->remove();

        $this->document = ['label' => 'Do a thing!'];
        $app['todos-collection']->insert($this->document);
    });

    describe('POST', function () {

        it('should return an error response if the todo already exists', function () {
            $this->client->request('POST', '/todos', [], [], [], '{"label":"Do a thing!"}');

            $response = $this->client->getResponse();

            assert($response->getStatusCode() === 422);
        });

    });

    describe('PUT', function () {

        it('should return an error response if the todo already exists', function () use ($edit) {
            $id = (string) $this->document['_id'];
            $this->client->request('PUT', "/todos/$id", [], [], [], '{"label":"Do a thing!"}');

            $response = $this->client->getResponse();

            assert($response->getStatusCode() === 422);
        });

    });
});
