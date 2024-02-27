<?php

namespace app\Service;


class CurlService
{

    public static function getProducts()
    {
        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "last_id" => "",
            'limit' => 1000
        ];

        $ch = curl_init('https://api-seller.ozon.ru/v2/product/list');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }


    // Получить данные из ozon
    public static function getOrders()
    {
        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "dir" => "desc",
            "filter" => [
                "since" => "2023-01-01T00:00:00.000Z",
                "status" => "",
                "to" => "2023-11-07T00:00:00.000Z",
            ],
            "limit" => 1000,
            "offset" => 0,
            "translit" => True,
            "with" => [
                "analytics_data" => True,
                "financial_data" => True
            ]
        ];


        $ch = curl_init('https://api-seller.ozon.ru/v2/posting/fbo/list');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }


    // Получить остатки из ozon
    public static function getStock()
    {
        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "limit" => 1000,
        ];


        $ch = curl_init('https://api-seller.ozon.ru/v2/analytics/stock_on_warehouses');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }






}