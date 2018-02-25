<?php

use Symfony\Component\Console\Application;
use user\ex2\SocketServer\RunCommand;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$application->add(new RunCommand());

$application->run();
