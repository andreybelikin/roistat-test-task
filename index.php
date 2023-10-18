<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Classes\Amo\AmoService;
use App\Classes\HttpClient;
use App\Classes\Responses\ErrorResponse;
use App\Classes\Responses\FormDataErrorResponse;
use App\Classes\Responses\SuccessResponse;
use App\Classes\Validation\DealValidator;

$httpClient = new HttpClient();

if (!DealValidator::isValid($_POST)) {
    $response = new FormDataErrorResponse('Некорректные данные формы. Попробуйте снова');
} else {

    try {
        $amoService = new AmoService($httpClient);
        $result = $amoService->sendDealToAmoCRM();

        if ($result) {
            $response = new SuccessResponse('Данные успешно отправлены. Спасибо за заполнение формы');
        }

    } catch (Exception $e) {
        $response = new ErrorResponse($e->getMessage());
    }
}

$httpClient->sendResponse($response);
