<?php

namespace app\Ozon\DTO;

class FunnelDTO
{
    public function __construct(
        public readonly ?int     $id,
        public readonly ?string $date,
        public readonly ?int    $view,
        public readonly ?int    $visit,
        public readonly ?int    $to_cart,
        public readonly ?int    $to_order
    )
    {
    }


    // создать DTO
    public static function ozon(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $id = null;
        $date = null;
        $view = null;
        $visit = null;
        $to_cart = null;
        $to_order = null;

        if (!empty($response['dimensions'][0]['id'])) $date = $response['dimensions'][0]['id'];
        if (!empty($response['metrics'][0])) $view = $response['metrics'][0];
        if (!empty($response['metrics'][1])) $visit = $response['metrics'][1];
        if (!empty($response['metrics'][2])) $to_cart = $response['metrics'][2];
        if (!empty($response['metrics'][3])) $to_order = $response['metrics'][3];

        return new self($id, $date, $view, $visit, $to_cart, $to_order);
    }
}
