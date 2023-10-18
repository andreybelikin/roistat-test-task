<?php

namespace App\Classes\Amo;

use App\Classes\Config\ApiConfig;
use App\Classes\Config\IntegrationConfig;
use App\Classes\Exceptions\AccessTokenExpired;
use App\Classes\HttpClient;
use App\Classes\Requests\ApiRequest;

class AmoService
{
    private HttpClient $httpClient;
    private AuthClient $authClient;
    private IntegrationConfig $integrationConfig;
    private ApiConfig $apiConfig;
    private ApiRequest $apiRequest;

    public function __construct(HttpClient $httpClient)
    {
        $this->integrationConfig = new IntegrationConfig();
        $this->apiConfig = new ApiConfig();
        $this->httpClient = $httpClient;
        $this->authClient = new AuthClient($this->httpClient, $this->integrationConfig);
        $this->apiRequest = new ApiRequest($this->authClient, $this->httpClient, $this->integrationConfig->getSubdomain());
    }

    /**
     * Создать сделку и контакт
     * @return bool
     */
    public function sendDealToAmoCRM(): bool
    {
        $apiMethod = $this->apiConfig->getApiMethod();
        $requestMethod = 'POST';
        $requestData = $this->setDealData();

        try {
            return $this->apiRequest->send($apiMethod, $requestMethod, $requestData);
        }
        catch (AccessTokenExpired $e) {
            $this->authClient->setAccessTokenByRefreshToken();
            return $this->apiRequest->send($apiMethod, $requestMethod, $requestData);
        }
    }

    private function setDealData(): array
    {
        return [
            [
                'name' => $this->apiConfig->getDealTitle(),
                'price' => (int)$_POST['sum'],
                'responsible_user_id' => $this->apiConfig->getResponsibleUser(),
                '_embedded' => [
                    'contacts' => [
                        [
                            'first_name' => $_POST['name'],
                            'responsible_user_id' => $this->apiConfig->getResponsibleUser(),
                            'custom_fields_values' => [
                                [
                                    'field_id' => $this->apiConfig->getFields()['email']['field_id'],
                                    'values' => [
                                        [
                                            'enum_id' => $this->apiConfig->getFields()['email']['enum_id'],
                                            'value' => $_POST['email'],
                                        ]
                                    ]
                                ],
                                [
                                    'field_id' => $this->apiConfig->getFields()['phone']['field_id'],
                                    'values' => [
                                        [
                                            'enum_id' => $this->apiConfig->getFields()['phone']['enum_id'],
                                            'value' => $_POST['phone'],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}