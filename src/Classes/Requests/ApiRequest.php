<?php

namespace App\Classes\Requests;

use App\Classes\Amo\AuthClient;
use App\Classes\Exceptions\AccessTokenExpired;
use App\Classes\Exceptions\SendDataError;
use App\Classes\HttpClient;

class ApiRequest
{
    private HttpClient $httpClient;
    private AuthClient $authClient;
    private array $baseParams;
    private string $baseurl;
    public function __construct(AuthClient $authClient, HttpClient $httpClient, string $subdomain)
    {
        $this->httpClient = $httpClient;
        $this->authClient = $authClient;
        $this->baseurl = 'https://' . $subdomain . '.amocrm.ru';
        $this->baseParams = [
            'userAgent' => 'amoCRM-API-client/1.0'
        ];
    }

    public function send(string $apiMethod, string $requestMethod, array $data): bool
    {
        $requestUrl = $this->baseurl . $apiMethod;
        $requestParams = $this->baseParams;
        $requestParams['method'] = $requestMethod;
        $requestParams['data'] = $data;
        $requestParams['headers'] = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->authClient->getAccessToken()
        ];
        list($resultCode, $result) = $this->httpClient->sendRequest($requestUrl, $requestParams);

        return $this->getApiRequestResult($resultCode, $result);
    }

    private function getApiRequestResult(int $resultCode, string $result): bool
    {
        if ($resultCode == 401) {
            throw new AccessTokenExpired('Access Token аннулирован', $resultCode, $result);
        }

        if (in_array($resultCode, [200, 201, 203, 204])) {
            return true;
        }

        if ($resultCode < 200 || $resultCode > 204) {
            throw new SendDataError('Ошибка при отправке данных', $resultCode, $result);
        }

        return false;
    }
}