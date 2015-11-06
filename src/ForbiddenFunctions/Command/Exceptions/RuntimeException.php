<?php

namespace NilPortugues\ForbiddenFunctions\Command\Exceptions;

class RuntimeException extends \RuntimeException
{
    public function __construct()
    {
        $message = "Malformatted YAML. Follow the YAML sample file structure";
        parent::__construct($message);
    }
}
