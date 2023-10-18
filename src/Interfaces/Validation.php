<?php

namespace App\Interfaces;

interface Validation
{
    public static function isValid(array $formData) :bool;
}