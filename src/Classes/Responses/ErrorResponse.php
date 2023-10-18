<?php

namespace App\Classes\Responses;

use App\Interfaces\Response;

class ErrorResponse implements Response
{
    private int $statusCode = 500;
    private string $message;
    public function __construct(string $message) {
        $this->message = $message;
    }

    public function toArray() :array
    {
        return ['code' => $this->statusCode, 'message' => $this->message];
    }

    public function getCode(): int
    {
        return $this->statusCode;
    }
}