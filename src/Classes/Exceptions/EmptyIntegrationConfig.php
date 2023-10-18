<?php

namespace App\Classes\Exceptions;

class EmptyIntegrationConfig extends \Exception
{
    protected $message = 'Данные не отправлены: не заполнен файл конфигурации интеграции';
}