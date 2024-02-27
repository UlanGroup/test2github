<?php

namespace app\Service;


class MoySkladApiService
{


    public static function getAssort()
    {
        $headers = [
            "Content-Type: application/json"
        ];
        $auth = array("admin@ulnoff", "tcUy8w42");
        $payload = [
            'limit' => 1000,
//            'groupBy' => "product"
        ];

        $ch = curl_init('https://online.moysklad.ru/api/remap/1.2/entity/assortment');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, implode(':', $auth));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);


        return json_decode($result, true);
    }

}