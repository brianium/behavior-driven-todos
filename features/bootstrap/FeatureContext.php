<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Event\ScenarioEvent;


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @BeforeSuite
     */
    public static function before()
    {
        $app = include __DIR__ . '/../../app/app.php';
        $collection = $app['mongo-client']->brianium->todos;
        $collection->drop();
    }
}
