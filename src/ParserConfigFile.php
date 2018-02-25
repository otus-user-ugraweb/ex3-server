<?php

namespace user\ex2\SocketServer;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ParserConfigFile
 * @package user\ex2\SocketServer
 */
class ParserConfigFile
{
    /**
     * @var string
     */
    private $PATH_TO_CONFIG_FILE = __DIR__ . '/../resource/config.yml';

    /**
     * ParserConfigFile constructor.
     */
    public function __construct()
    {
    }

    /**
     * method return a port
     * @return string
     */
    public function getPort()
    {
        $configurationData = $this->parseConfigFile();
        $port = $configurationData['port'];

        return $port;
    }

    /**
     * method for parsing
     * @return mixed
     */
    public function parseConfigFile()
    {
        $configurationData = [];

        try {
            $configurationData = Yaml::parseFile($this->PATH_TO_CONFIG_FILE, 256);
        } catch (ParseException $e) {
            printf('Unable to parse the YAML file: %s', $e->getMessage());
        }

        return $configurationData;
    }
}