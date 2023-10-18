<?php

namespace App\Classes\Exceptions;

class PlainException extends \Exception
{
    private string $requestResult;

    public function __construct(string $message, int $code, string $result)
    {
        $this->requestResult = $result;
        parent::__construct($message, $code);
    }

    public function getResult() :string
    {
        return $this->requestResult;
    }
}