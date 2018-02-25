<?php

namespace user\ex2\SocketServer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 * @package user\ex2\SocketServer
 */
class RunCommand extends Command
{
    /**
     * method to configure a application
     */
    protected function configure()
    {
        $this
            ->setName('socketServer')
            ->addArgument('address', InputArgument::REQUIRED);
    }

    /**
     * method to execute a application
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \HttpSocketException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $address = $input->getArgument('address');

        $parserConfigFile = new ParserConfigFile();
        $port = $parserConfigFile->getPort();

        $socketServer = new SocketServer($address, $port);
        $socketServer->run();
    }
}