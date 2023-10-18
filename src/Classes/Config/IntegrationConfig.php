<?php

namespace App\Classes\Config;

use App\Classes\Exceptions\EmptyIntegrationConfig;

class IntegrationConfig
{
    private string $configPath = __DIR__ . '/../../../config/integration-config.php';
    private string $clientId;
    private string $clientSecret;
    private string $subdomain;
    private string $authorizationCode;
    private string $redirectUri;

    public function __construct()
    {
        if (file_exists($this->configPath)) {
            $this->checkConstsForFilling() ?: throw new EmptyIntegrationConfig('Нет конфигурационного файла или он пуст');
            $this->setConfigVariables();
        } else {
            throw new EmptyIntegrationConfig('Нет конфигурационного файла или он пуст');
        }
    }

    private function checkConstsForFilling(): bool
    {
        require_once $this->configPath;

        if (SUBDOMAIN && CLIENT_SECRET && CLIENT_ID && AUTHORIZATION_CODE && REDIRECT_URI) {
            return true;
        }

        return false;
    }

    private function setConfigVariables(): void
    {
        $this->subdomain = SUBDOMAIN;
        $this->clientId = CLIENT_ID;
        $this->clientSecret = CLIENT_SECRET;
        $this->authorizationCode = AUTHORIZATION_CODE;
        $this->redirectUri = REDIRECT_URI;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getSubdomain(): string
    {
        return $this->subdomain;
    }

    public function getAuthorizationCode(): string
    {
        return $this->authorizationCode;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }
}