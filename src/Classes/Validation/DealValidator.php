<?php

namespace App\Classes\Validation;

use App\Interfaces\Validation;

class DealValidator implements Validation
{
    public static function isValid(array $formData) :bool {
        if (empty($formData)) {
            return false;
        }
        return self::validateEmail($formData['email']) && self::validateSum($formData['sum']) && self::validateName($formData['name']) && self::validatePhone($formData['phone']);
    }

    private static function validateEmail(string $email) :bool
    {
        return $email && self::filterValue($email, FILTER_VALIDATE_EMAIL);
    }

    private static function validateName(string $nameValue) :bool
    {
        $nameValue = htmlspecialchars(trim($nameValue));
        return $nameValue && self::filterValue($nameValue, FILTER_UNSAFE_RAW);
    }

    private static function validateSum(int $sum) :bool
    {
        return $sum && self::filterValue($sum, FILTER_VALIDATE_INT);
    }

    private static function validatePhone(string $phoneNumber) :bool
    {
        return $phoneNumber && preg_match('/\+\d{11}/', $phoneNumber);
    }

    private static function filterValue(mixed $value, int $filter) :bool {
        return filter_var($value, $filter);
    }
}