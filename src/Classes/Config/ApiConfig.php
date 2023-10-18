<?php

namespace App\Classes\Config;

use App\Classes\Exceptions\EmptyIntegrationConfig;

class ApiConfig
{
    private string $configPath = __DIR__ . '/../../../config/api-config.php';
    private string $responsibleUser;
    private string $dealTitle;
    private array $fields;
    private string $apiMethod;

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

        if (API_METHOD && DEAL_TITLE && FIELDS && RESPONSIBLE_USER) {
            return true;
        }

        return false;
    }

    private function setConfigVariables(): void
    {
        $this->apiMethod = API_METHOD;
        $this->dealTitle = DEAL_TITLE;
        $this->fields = FIELDS;
        $this->responsibleUser = RESPONSIBLE_USER;
    }

    public function getApiMethod(): string
    {
        return $this->apiMethod;
    }

    /**
     * @return string
     */
    public function getDealTitle(): string
    {
        return $this->dealTitle;
    }

    /**
     * @return string
     */
    public function getResponsibleUser(): int
    {
        return $this->responsibleUser;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}