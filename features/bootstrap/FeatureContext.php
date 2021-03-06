<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Event\ScenarioEvent;
use Symfony\Component\Process\Process;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Silex\Application
     */
    private static $app;

    /**
     * @var Process
     */
    private static $process;

    /**
     * @BeforeSuite
     */
    public static function beforeAll()
    {
        $public = realpath(__DIR__ . '/../../public');
        self::$process = new Process("php -S localhost:4000 -t $public");
        self::$process->start();
    }

    /**
     * @AfterSuite
     */
    public static function afterAll()
    {
        self::$process->stop();
    }

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
     * @When I click :arg1
     */
    public function iClick($selector)
    {
        $element = $this
            ->getSession()
            ->getPage()
            ->find('css', $selector);

        if ($element == null) {
            throw new Exception("Element $selector not found");
        }

        $element->click();
    }

    /**
     * Fetch the mongo collection of todos
     */
    private function getTodoCollection()
    {
        if (!isset(self::$app)) {
            self::$app = include __DIR__ . '/../../app/app.php';
        }
        return self::$app['todos-collection'];
    }
}
