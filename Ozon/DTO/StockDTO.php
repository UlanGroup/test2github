<?php

namespace app\Ozon\DTO;

class StockDTO
{

    public function __construct(
        public readonly ?int    $product_id,
        public readonly string $sku,
        public readonly int     $category_id,
        public readonly string  $name,
        public readonly ?string $barcode,
        public readonly ?float  $price,
        public readonly ?string $image,
        public readonly int     $status,
    )
    {
    }


    // создать DTO
    public static function ozon(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $offer_id = null;
        $sku = null;
        $category_id = 0;
        $name = null;
        $barcode = null;
        $price = 0.00;
        $image = null;
        $status = 1;

        if (!empty($response['id'])) $id = $response['id'];
        if (!empty($response['offer_id'])) $offer_id = $response['offer_id'];
        if (!empty($response['fbo_sku'])) (int)$sku = $response['fbo_sku'];
        if (!empty($response['category_id'])) (int)$category_id = $response['category_id'];
        if (!empty($response['name'])) $name = $response['name'];
        if (!empty($response['barcode'])) $barcode = $response['barcode'];
        if (!empty($response['marketing_price'])) $price = $response['marketing_price'];
        if (!empty($response['primary_image'])) $image = $response['primary_image'];
        if (!empty($response['status'])) $status = 0;

        return new self($id, $offer_id, $sku, $category_id, $name, $barcode, $price, $image, $status);
    }

}
