<?php

namespace App\Classes\Responses;

use App\Interfaces\Response;

class FormDataErrorResponse implements Response
{
    private int $statusCode = 400;
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function toArray(): array
    {
        return ['code' => $this->statusCode, 'message' => $this->message];
    }

    public function getCode(): int
    {
        return $this->statusCode;
    }
}