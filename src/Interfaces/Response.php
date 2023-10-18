<?php

namespace App\Interfaces;

interface Response
{
    public function __construct(string $message);

    public function toArray() :array;
    public function getCode() :int;
}