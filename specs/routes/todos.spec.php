<?php
describe('/todos/{id}', function () {
    describe('POST', function () {

        beforeEach(function () {
            $app = $this->getHttpKernelApplication();
            $app['todos-collection']->remove();
            $app['todos-collection']->insert(['label' => 'Do a thing!']);
        });

        it('should return an error response if the todo already exists', function () {
            $this->client->request('POST', '/todos', [], [], [], '{"label":"Do a thing!"}');

            $response = $this->client->getResponse();

            assert($response->getStatusCode() === 400);
        });
    });
});
