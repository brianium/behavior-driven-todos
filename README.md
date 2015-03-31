Behavior Driven Todos
=====================

Demonstrating a full BDD cycle with [Peridot](http://peridot-php.github.io/) and [Behat](http://docs.behat.org/en/v2.5/).

## The Application

The application is a simple todo application. The only external dependencies needed to run this appliction are MongoDB and the related php extension.

## Features

Features are run via Behat and the [Mink extension](https://github.com/Behat/MinkExtension). Automagic download and startup of selenium server is handled by the Peridot [WebDriverManager](https://github.com/peridot-php/webdriver-manager) library.

A development server is automatically started using the Symfony [Process component](http://symfony.com/doc/current/components/process.html).

Running the features is easy:

```
$ vendor/bin/behat
```

## Specs

Unit level tests for code behavior are written for the Peridot testing framework. Peridot specs can be run like so:

```
$ vendor/bin/peridot
```

Some light functional tests using [BrowserKit](https://github.com/symfony/BrowserKit) have also been written using the Peridot [HttpKernelPlugin](https://github.com/peridot-php/peridot-httpkernel-plugin), and they can be located in the `specs/routes` directory.

## Motivation

The point of this application is to demonstrate a full BDD cycle where we start with acceptance criteria written in gherkin and run with Behat. As we move to code and unit level tests, we test behavior with an elegant BDD tool like Peridot.

The point is to demonstrate using multiple great tools for a happy BDD ecosystem :)
