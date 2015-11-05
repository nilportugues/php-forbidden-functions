<?php


namespace NilPortugues\ForbiddenFunctions\Console;

use NilPortugues\ForbiddenFunctions\Command\FixerCommand;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * Construct method
     */
    public function __construct()
    {
        $name = <<<NAME
-------------------------------------------------
 PHP Forbidden Functions Checker
-------------------------------------------------
NAME;

        parent::__construct($name);
    }

    /**
     * Initializes all the composer commands
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new FixerCommand();

        return $commands;
    }
}
