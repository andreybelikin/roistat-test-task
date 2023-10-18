<?php

namespace App\Classes\Requests;

use App\Classes\Config\IntegrationConfig;
use App\Classes\Exceptions\AuthDataRevoked;
use App\Classes\Exceptions\ReceivingTokensError;
use App\Classes\HttpClient;

class AuthRequest
{
    private HttpClient $httpClient;
    private IntegrationConfig $integrationConfig;
    private array $baseParams;
    private string $requestMethod = 'POST';
    private string $requestUrl;
    public function __construct(IntegrationConfig $integrationConfig, HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->integrationConfig = $integrationConfig;
        $this->requestUrl = 'https://' . $integrationConfig->getSubdomain() . '.amocrm.ru' . '/oauth2/access_token';
        $this->baseParams = [
            'userAgent' => 'amoCRM-API-client/1.0',
            'data' => [
                'client_id'     => $this->integrationConfig->getClientId(),
                'client_secret' => $this->integrationConfig->getClientSecret(),
                'redirect_uri'  => $this->integrationConfig->getRedirectUri(),
            ],
            'method' => $this->requestMethod,
        ];
    }

    public function send(string $grantType, string $refreshToken = ''): string
    {
        $requestParams = $this->baseParams;
        if ($grantType == 'authorization_code') {
            $requestParams['data']['grant_type'] = $grantType;
            $requestParams['data']['code'] = $this->integrationConfig->getAuthorizationCode();
        }

        if ($grantType == 'refresh_token') {
            $requestParams['data']['grant_type'] = $grantType;
            $requestParams['data']['refresh_token'] = $refreshToken;
        }

        list($resultCode, $result) = $this->httpClient->sendRequest($this->requestUrl, $requestParams);
        return $this->handleAuthResponse($resultCode, $result);
    }

    private function handleAuthResponse(int $code, string $responseData): string
    {
        if ($code == 400) {
            throw new AuthDataRevoked('Код или токен аннулирован', $code, $responseData);
        }

        if (in_array($code, [200, 201, 203, 204])) {
            return $responseData;
        }

        if ($code < 200 || $code > 204) {
            throw new ReceivingTokensError('Ошибка при получении токенов', $code, $responseData);
        }

        return '';
    }
}