<?php

namespace app\Order\DTO;

class ProductDTO
{

    public function __construct(
        public readonly int    $id,
        public readonly string $offer_id,
        public readonly ?float $price,
        public readonly int    $status,
    )
    {
    }


    // создать DTO
    public static function ozon(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $id = null;
        $offer_id = null;
        $price = 0.00;
        $status = 1;

        if (!empty($response['product_id'])) $id = $response['product_id'];
        if (!empty($response['offer_id'])) $offer_id = $response['offer_id'];
        if (!empty($response['price'])) $price = $response['price'];
        if (!empty($response['status'])) $status = 0;

        return new self($id, $offer_id, $price, $status);
    }

}
