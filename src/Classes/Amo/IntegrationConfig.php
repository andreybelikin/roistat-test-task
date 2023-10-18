<?php

namespace App\Classes\Amo;

use App\Classes\Exceptions\EmptyIntegrationConfig;

class IntegrationConfig
{
    private string $configPath = __DIR__ . '/../../../config/config.php';
    private string $clientId;
    private string $clientSecret;
    private string $subdomain;
    private string $authorizationCode;
    private string $redirectUri;
    private int $responsibleUser;
    private int $pipelineId;

    public function __construct()
    {
        if (file_exists($this->configPath)) {
            require_once $this->configPath;
            $this->checkConstsForFilling() ?: throw new EmptyIntegrationConfig('Нет конфигурационного файла или он пуст');
            $this->setConfigVariables();
        } else {
            throw new EmptyIntegrationConfig('Нет конфигурационного файла или он пуст');
        }
    }

    private function checkConstsForFilling() :bool
    {
        if (SUBDOMAIN && CLIENT_SECRET && CLIENT_ID && AUTHORIZATION_CODE && REDIRECT_URI && RESPONSIBLE_USER) {
            return true;
        }
        return false;
    }

    private function setConfigVariables()
    {
        $this->subdomain = SUBDOMAIN;
        $this->clientId = CLIENT_ID;
        $this->clientSecret = CLIENT_SECRET;
        $this->authorizationCode = AUTHORIZATION_CODE;
        $this->redirectUri = REDIRECT_URI;
        $this->responsibleUser = RESPONSIBLE_USER;
        $this->pipelineId = PIPELINE_ID;
    }

    public function getClientId() :string
    {
        return $this->clientId;
    }
    public function getClientSecret() :string
    {
        return $this->clientSecret;
    }

    public function getSubdomain() :string
    {
        return $this->subdomain;
    }

    public function getAuthorizationCode() :string
    {
        return $this->authorizationCode;
    }

    public function getRedirectUri() :string
    {
        return $this->redirectUri;
    }

    public function getResponsibleUser() :string
    {
        return $this->responsibleUser;
    }

    public function getPipelineId() :string
    {
        return $this->pipelineId;
    }
}