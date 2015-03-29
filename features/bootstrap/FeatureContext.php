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
    private static $app;

    /**
     * @BeforeScenario
     */
    public function beforeEach()
    {
        $collection = $this->getTodoCollection();
        $collection->drop();
    }

    /**
     * @Then I should see :arg1 after waiting
     */
    public function iShouldSeeAfterWaiting($text)
    {
        $this->getSession()->wait(10000, "document.documentElement.innerHTML.indexOf('$text') > -1");
        $this->assertPageContainsText($text);
    }

    /**
     * @Given I have a todo :arg1
     */
    public function iHaveATodo($todoText)
    {
        $collection = self::getTodoCollection();
        $collection->insert(['label' => $todoText, 'done' => false]);
    }

    /**
     * @Then I should see :arg2 :arg1 element(s) after waiting
     */
    public function iShouldSeeElementAfterWaiting($number, $selector)
    {
        $this->getSession()->wait(10000, "document.querySelectorAll('$selector').length === $number");
        $this->assertNumElements($number, $selector);
    }

    /**
     * @Given I have a done todo :arg1
     */
    public function iHaveADoneTodo($todoText)
    {
        $collection = self::getTodoCollection();
        $collection->insert(['label' => $todoText, 'done' => true]);
    }

    /**
     * Fetch the mongo collection of todos
     */
    private function getTodoCollection()
    {
        if (!isset(self::$app)) {
            self::$app = include __DIR__ . '/../../app/app.php';
        }
        $collection = self::$app['mongo-client']->brianium->todos;
        return $collection;
    }
}
