<?php

namespace App\Classes;

use App\Interfaces\Response;

class HttpClient
{
    public function __construct() {}

    /**
     * Отправляет запрос и возвращает ответ обратно
     * @param string $link
     * @param array $params
     * @return bool|string
     */
    public function sendRequest(string $link, array $params): array
    {
        $curl = $this->setCurlOptions($link, $params);
        $result = curl_exec($curl);
        $resultCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        return [$resultCode, $result];
    }

    /**
     * Задает curl-опции для запроса
     * @param string $link
     * @param array $params
     * @return \CurlHandle
     */
    private function setCurlOptions(string $link, array $params): \CurlHandle
    {
        $curl =  curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT, $params['userAgent']);
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $params['headers'] ?? ['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $params['method']);
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($params['data'], JSON_UNESCAPED_UNICODE));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);

        return $curl;
    }

    /**
     * Отправляет ответ для формы на фронте. Ошибка (текст из Exception) или Данные успешно сохранены
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response): void
    {
        header('Content-Type: application/json');
        http_response_code($response->getCode());
        echo json_encode($response->toArray(), JSON_UNESCAPED_UNICODE);
    }
}