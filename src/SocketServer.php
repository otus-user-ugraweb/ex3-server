<?php

namespace user\ex2\SocketServer;

use HttpSocketException;

/**
 * Class SocketServer
 * @package user\ex2\SocketServer
 */
class SocketServer
{

    /**
     * @var string
     */
    private $port;
    /**
     * @var string
     */
    private $address;
    /**
     * @var resource
     */
    private $socket;
    /**
     * @var int
     */
    private $sizeBuffer;

    /**
     * SocketServer constructor.
     * @param $address
     * @param $port
     * @param int $sizeBuffer
     */
    public function __construct($address, $port, $sizeBuffer = 2048)
    {
        $this->address = $address;
        $this->port = $port;
        $this->sizeBuffer = $sizeBuffer;
    }

    /**
     * method to start a socket-server
     * @throws HttpSocketException
     */
    public function run()
    {
        /**
         * call method for initialize socket-server
         */
        $this->initializeSocket();

        while (true) {
            if (($resourceSocket = socket_accept($this->socket)) < 0) {
                throw new HttpSocketException();
            }

            while (true) {
                if (false === ($buffer = $this->readMessage($resourceSocket))) {
                    throw new HttpSocketException();
                }

                if ($buffer == 'destroy') {
                    $this->destroySocket();
                    break 2;
                }

                if ($buffer == 'SIGHUP') {
                    $parserConfigFile = new ParserConfigFile();
                    $port = $parserConfigFile->getPort();

                    if($this->port != $port) {
                        $this->port = $port;
                        $message = sprintf("Now will change port: %s", $port);
                        $this->sendMessage($resourceSocket, $message);
                        $this->destroySocket();
                        $this->initializeSocket();
                    }

                    break 1;
                }

                $validityStatus = (new Validator($buffer))->checkSequence();

                $message = sprintf("The sequence is received: (%s). Valid - %s \n", $buffer, $validityStatus);
                echo $message;

                $this->sendMessage($resourceSocket, $validityStatus);

                break 1;
            }
        }
    }

    /**
     * method to initialize a socket-server
     * @throws HttpSocketException
     */
    public function initializeSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (((socket_bind($this->socket, $this->address, $this->port)) < 0) ||
            ((socket_listen($this->socket, SOMAXCONN)) < 0)) {
            throw new HttpSocketException();
        }

        echo "Сокет успешно открыт. \n";
    }

    /**
     * method to send a message in socket-server
     * @param $resourceSocket
     * @param $message
     */
    private function sendMessage(&$resourceSocket, $message)
    {
        $lengthMessage = strlen($message);
        socket_write($resourceSocket, $message, $lengthMessage);
    }

    /**
     * method to read a message from socket-server
     * @param $resourceSocket
     * @return string
     */
    private function readMessage(&$resourceSocket)
    {
        return socket_read($resourceSocket, $this->sizeBuffer);
    }

    /**
     * method to close a socket-server
     */
    private function destroySocket()
    {
        socket_close($this->socket);
    }
}