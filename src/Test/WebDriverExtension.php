<?php
namespace Brianium\Todos\Test;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Symfony\Component\DependencyInjection\Definition;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WebDriverExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'webdriver';
    }

    /**
     * {@inheritdoc}
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        //nothing to initialize
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        //nothing to configure
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition('Brianium\Todos\Test\WebDriverListener');
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, array('priority' => 0));
        $container->setDefinition('todos.listener.webdriver', $definition);
    }

    /**
     * {@inheritdoc}
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        //nothing to process
    }
}
