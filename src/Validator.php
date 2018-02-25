<?php

namespace user\ex2\SocketServer;

use InvalidArgumentException;
use otus\user\ugraweb\ex1\ValidatorSequence;

/**
 * Class Validator
 * @package user\ex2\SocketServer
 */
class Validator
{
    private $sequence;

    /**
     * Validator constructor.
     * @param $sequence
     */
    public function __construct($sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * method to configure a sequence
     * @return string
     */
    public function checkSequence()
    {
        try {
            $validatorSequence = new ValidatorSequence($this->sequence);
        } catch (InvalidArgumentException $exception) {
            return "sequence is not valid";
        }

        if ($validatorSequence->checkSequence()) {
            return "sequence is correct";
        }

        return "sequence is not correct";
    }
}