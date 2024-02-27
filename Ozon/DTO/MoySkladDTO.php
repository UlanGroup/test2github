<?php

namespace app\Ozon\DTO;

class MoySkladDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $article,
        public readonly ?int    $stock,
        public readonly ?int    $reserve,
        public readonly ?int    $inTransit,
        public readonly ?int    $qty,
        public readonly ?int    $buyPrice,
    )
    {
    }

    // создать DTO
    public static function assortment(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $id = null;
        $name = null;
        $article = null;
        $stock = null;
        $reserve = null;
        $inTransit = null;
        $qty = null;
        $buyPrice = null;

        if (!empty($response['id'])) $id = $response['id'];
        if (!empty($response['name'])) $name = $response['name'];
        if (!empty($response['article'])) $article = $response['article'];
        if (!empty($response['stock'])) $stock = $response['stock'];
        if (!empty($response['reserve'])) $reserve = $response['reserve'];
        if (!empty($response['inTransit'])) $inTransit = $response['inTransit'];
        if (!empty($response['quantity'])) $qty = $response['quantity'];
        if (!empty($response['buyPrice']['value'])) $buyPrice = $response['buyPrice']['value'];


        return new self($id, $name, $article, $stock, $reserve, $inTransit, $qty, $buyPrice);
    }
}
