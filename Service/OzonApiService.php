<?php

namespace app\Service;


class OzonApiService
{

    public static function getProducts(bool $archived = false)
    {
        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "last_id" => '',
            'limit' => 1000,
        ];

        if ($archived) $payload['filter']['visibility'] = 'ARCHIVED';

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


    public static function getProduct(int $id)
    {
        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "product_id" => $id,
            "sku" => 0
        ];

        $ch = curl_init('https://api-seller.ozon.ru/v2/product/info');
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


    public static function getProductBySku(int $sku)
    {
        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "sku" => $sku
        ];

        $ch = curl_init('https://api-seller.ozon.ru/v2/product/info');
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
    public static function getOrders($end = false)
    {
        date_default_timezone_set("Europe/Moscow");

        if (empty($end)) {
            $end = date('Y-m-d\TH:i:s.000\Z');
            $srt = date('Y-m-d\TH:i:s.000\Z', strtotime("$end -7 day"));
        } else {
            $end = date('Y-m-d\TH:i:s.000\Z', strtotime($end));
            $srt = date('Y-m-d\TH:i:s.000\Z', strtotime("$end -7 day"));
        }

        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "dir" => "desc",
            "filter" => [
                "since" => $srt, // "2023-01-01T00:00:00.000Z"
                "status" => "",
                "to" => $end,
            ],
            "limit" => 100,
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

    // Получить транзакции из ozon
    public static function getTransaction(string $posting_number)
    {

        date_default_timezone_set("Europe/Moscow");

        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "date" => [
                "from" => "2022-01-01T00:00:00.000Z", // "2023-01-01T00:00:00.000Z"
                "to" => date('Y-m-d\TH:i:s.000\Z'),
            ],
            "posting_number" => $posting_number,
            "transaction_type" => "all"
        ];


        $ch = curl_init('https://api-seller.ozon.ru/v3/finance/transaction/totals');
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


    // получить воронку
    public static function getFunnel()
    {
        $headers = [
            "Host: api-seller.ozon.ru",
            "Client-Id: 2019",
            "Api-Key: 059bca6b-d650-441c-98f1-cff84b70fcde",
            "Content-Type: application/json",
        ];

        $payload = [
            "date_from" => "2024-01-01",
            "date_to" => date('Y-m-d'),
            "dimension" => ["day"],
            "metrics" => [
                "session_view",
                "session_view_pdp",
                "hits_tocart",
                "ordered_units"
            ],
            "offset" => 0,
            "limit" => 1000
        ];

            $ch = curl_init('https://api-seller.ozon.ru/v1/analytics/data');
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