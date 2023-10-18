<?php

namespace App\Classes\Amo;

use App\Classes\Exceptions\AccessTokenExpired;
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
        $this->baseurl = $subdomain . '.amocrm.ru';
        $this->baseParams = [
            'userAgent' => 'amoCRM-API-client/1.0',
            'headers' => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->authClient->getAccessToken()
            ]
        ];
    }

    public function sendApiRequest(string $apiMethod, string $requestMethod, array $data) :int
    {
        $requestUrl = $this->baseurl . $apiMethod;
        $requestParams = $this->baseParams;
        $requestParams['method'] = $requestMethod;
        $requestParams['data'] = $data;
        list($resultCode, $result) = $this->httpClient->sendRequest($requestUrl, $requestParams);
        if ($resultCode == 401) {
            throw new AccessTokenExpired('Access Token аннулирован', $resultCode, $result);
        }
        return $resultCode;
    }
}