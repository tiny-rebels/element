<?php

namespace app\console;

use app\console\commands\{
    DisplayAppModeCommand,
    generators\ConsoleGeneratorCommand,
    generators\ControllerGeneratorCommand,
    generators\DataModelGeneratorCommand,
    SayHelloCommand,
    StartServerCommand};

class Kernel {

    protected $commands = [

        /* APPLICATION */
        DisplayAppModeCommand::class,
        SayHelloCommand::class,
        StartServerCommand::class,
        /* MAKE */
        ConsoleGeneratorCommand::class,
        ControllerGeneratorCommand::class,
        DataModelGeneratorCommand::class,

    ];

    public function getCommands() {

        return $this->commands;
    }
}