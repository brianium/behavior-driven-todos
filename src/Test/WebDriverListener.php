<?php
namespace Brianium\Todos\Test;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Behat\Testwork\EventDispatcher\Event\SuiteTested;
use Behat\Testwork\EventDispatcher\Event\ExerciseCompleted;
use Peridot\WebDriverManager\Manager;

class WebDriverListener implements EventSubscriberInterface
{
    /**
     * @var Peridot\WebDriverManager\Process\SeleniumProcess
     */
    protected $process;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            SuiteTested::BEFORE   => array('startSelenium', 10),
            ExerciseCompleted::AFTER => array('stopSelenium', -11)
        );
    }

    /**
     * Ensure selenium binaries are up to date
     * and then start selenium
     */
    public function startSelenium()
    {
        $manager = new Manager();
        fwrite(STDOUT, "Updating and starting selenium\n");
        $manager->update();
        $this->process = $manager->startInBackground(4444, ['-log', tempnam(sys_get_temp_dir(), 'selenium_')]);
        usleep(500000);
    }

    /**
     * Shut selenium server down
     */
    public function stopSelenium()
    {
        fwrite(STDOUT, "Stopping selenium\n");
        $this->process->close();
    }
}
