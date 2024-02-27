<?php

namespace app\Wildberries\Service;


class WbService
{


    // Получить данные
    public static function getSales(string $dateFrom)
    {
        $accessToken = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjMxMjI1djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTcyMjEwNjQyMSwiaWQiOiIzZTAwMzhkZS04ZTZhLTRiZmMtODczZS1mNDJmYjRkZTJkNzkiLCJpaWQiOjM0NTE1ODU0LCJvaWQiOjU5NTUwLCJzIjo1MTAsInNpZCI6ImRiN2NkMTE2LTE2NmEtNTY3MS05MTc2LTlhYTYwNmQ2OTMyZiIsInQiOmZhbHNlLCJ1aWQiOjM0NTE1ODU0fQ.5supy-WVu-0KwzAD77u7cME-aJuWUVPg8h7dHhq2kGQy4RDJ17cypfwUQzTvtV1nPVe3FGsKn4vl9qQMP8PMzw';

        if (empty($dateFrom)) $dateFrom = date('Y-m-d');


        $ch = curl_init('https://statistics-api.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dateFrom);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }


    public static function getOrders(string $dateFrom)
    {
        $accessToken = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjMxMjI1djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTcyMjEwNjQyMSwiaWQiOiIzZTAwMzhkZS04ZTZhLTRiZmMtODczZS1mNDJmYjRkZTJkNzkiLCJpaWQiOjM0NTE1ODU0LCJvaWQiOjU5NTUwLCJzIjo1MTAsInNpZCI6ImRiN2NkMTE2LTE2NmEtNTY3MS05MTc2LTlhYTYwNmQ2OTMyZiIsInQiOmZhbHNlLCJ1aWQiOjM0NTE1ODU0fQ.5supy-WVu-0KwzAD77u7cME-aJuWUVPg8h7dHhq2kGQy4RDJ17cypfwUQzTvtV1nPVe3FGsKn4vl9qQMP8PMzw';

        if (empty($dateFrom)) $dateFrom = date('Y-m-d');

        $ch = curl_init('https://statistics-api.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $dateFrom);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public static function getStock(string $dateFrom)
    {
        $accessToken = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjMxMjI1djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTcyMjEwNjQyMSwiaWQiOiIzZTAwMzhkZS04ZTZhLTRiZmMtODczZS1mNDJmYjRkZTJkNzkiLCJpaWQiOjM0NTE1ODU0LCJvaWQiOjU5NTUwLCJzIjo1MTAsInNpZCI6ImRiN2NkMTE2LTE2NmEtNTY3MS05MTc2LTlhYTYwNmQ2OTMyZiIsInQiOmZhbHNlLCJ1aWQiOjM0NTE1ODU0fQ.5supy-WVu-0KwzAD77u7cME-aJuWUVPg8h7dHhq2kGQy4RDJ17cypfwUQzTvtV1nPVe3FGsKn4vl9qQMP8PMzw';

        if (empty($dateFrom)) $dateFrom = date('Y-m-d');

        $ch = curl_init('https://statistics-api.wildberries.ru/api/v1/supplier/stocks?dateFrom=' . $dateFrom);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public static function getProduct()
    {
        $accessToken = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjMxMjI1djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTcyMjEwNjQyMSwiaWQiOiIzZTAwMzhkZS04ZTZhLTRiZmMtODczZS1mNDJmYjRkZTJkNzkiLCJpaWQiOjM0NTE1ODU0LCJvaWQiOjU5NTUwLCJzIjo1MTAsInNpZCI6ImRiN2NkMTE2LTE2NmEtNTY3MS05MTc2LTlhYTYwNmQ2OTMyZiIsInQiOmZhbHNlLCJ1aWQiOjM0NTE1ODU0fQ.5supy-WVu-0KwzAD77u7cME-aJuWUVPg8h7dHhq2kGQy4RDJ17cypfwUQzTvtV1nPVe3FGsKn4vl9qQMP8PMzw';

        $payload = [
            "settings" => [
                "cursor" => [
                    "limit" => 1000],
                "filter" => ["withPhoto" => -1]
            ]
        ];

        $ch = curl_init('https://suppliers-api.wildberries.ru/content/v2/get/cards/list');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
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