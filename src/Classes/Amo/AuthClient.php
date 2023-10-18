<?php

namespace App\Classes\Amo;

use App\Classes\Config\IntegrationConfig;
use App\Classes\HttpClient;
use App\Classes\Requests\AuthRequest;

class AuthClient
{
    private HttpClient $httpClient;
    private IntegrationConfig $integrationConfig;
    private AuthRequest $authRequest;
    private string $tokensFilePath = __DIR__ . '/../../../tokens/tokens.txt';
    private string $accessToken;
    private string $refreshToken;

    public function __construct(HttpClient $httpClient, IntegrationConfig $integrationConfig) {
        $this->httpClient = $httpClient;
        $this->integrationConfig = $integrationConfig;
        $this->authRequest = new AuthRequest($this->integrationConfig, $this->httpClient);
        $this->setTokens();
    }

    /**
     * Задает токены. Если нет в файле, запрашивает с помощью кода авторизации интеграции
     * @return void
     */
    private function setTokens(): void
    {
        if ($this->isSetInFile()) {
            $this->setTokensFromFile();
        } else {
            $this->setTokensByAuthorizationCode();
        }
    }

    private function setTokensFromFile(): void
    {
        $tokens = file_get_contents($this->tokensFilePath);
        $tokens = json_decode($tokens, true);

        $this->accessToken = $tokens['access_token'];
        $this->refreshToken = $tokens['refresh_token'];
    }

    private function isSetInFile(): bool
    {
        if (file_exists($this->tokensFilePath)) {
            return true;
        }

        return false;
    }

    private function setTokensByAuthorizationCode(): void
    {
        $responseData = $this->authRequest->send('authorization_code');
        $this->saveNewTokensInFile($responseData);
        $responseData = json_decode($responseData, true);
        $this->accessToken = $responseData['access_token'];
        $this->refreshToken = $responseData['refresh_token'];
    }

    /**
     * Сохраняет токены в файле /tokens/tokens.txt
     * @return void
     */
    private function saveNewTokensInFile(string $tokensData): void
    {
        file_put_contents($this->tokensFilePath, $tokensData);
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessTokenByRefreshToken(): void
    {
        $responseData = $this->authRequest->send('refresh_token', $this->refreshToken);
        $this->saveNewTokensInFile($responseData);
        $responseData = json_decode($responseData, true);
        $this->accessToken = $responseData['access_token'];
        $this->refreshToken = $responseData['refresh_token'];
    }
}