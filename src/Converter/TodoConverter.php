<?php
namespace Brianium\Todos\Converter;

use MongoCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoConverter
{
    /**
     * @var MongoCollection
     */
    private $todos;

    /**
     * @param MongoCollection $todos
     */
    public function __construct(MongoCollection $todos)
    {
        $this->todos = $todos;
    }

    /**
     * Convert an id into a document
     *
     * @param string $id
     * @return array
     */
    public function convert($id)
    {
        $todo = $this->todos->findOne(['_id' => new \MongoId($id)]);

        if ($todo === null) {
            throw new NotFoundHttpException("Todo not found");
        }

        return $todo;
    }
}
