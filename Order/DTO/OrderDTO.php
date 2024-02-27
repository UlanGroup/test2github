<?php

namespace app\Order\DTO;

class OrderDTO
{

    public function __construct(
        public readonly int     $market_order_id,
        public readonly ?string $offer_id,
        public readonly float   $price,
        public readonly int     $quantity,
        public readonly ?string $region,
        public readonly ?string $warehouse,
        public readonly ?string $created,
        public readonly int     $status,
    )
    {
    }


    // создать DTO
    public static function ozon(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $market_order_id = null;
        $offer_id = null;
        $price = 0.00;
        $quantity = 0;
        $region = null;
        $warehouse = null;
        $created = null;
        $status = null;

        if (!empty($response['order_id'])) $market_order_id = $response['order_id'];

        foreach ($response['products'] as $product) {
            if (!empty($product['offer_id'])) $offer_id = $product['offer_id'];
            if (!empty($product['price'])) $price = $product['price'];
            if (!empty($product['quantity'])) $quantity = $product['quantity'];
        }

        if (!empty($response['analytics_data']['region'])) $region = $response['analytics_data']['region'];
        if (!empty($response['analytics_data']['warehouse_name'])) $warehouse = $response['analytics_data']['warehouse_name'];
        if (!empty($response['created_at'])) $created = date('Y-m-d H:i:s', strtotime($response['created_at']));

        if (!empty($response['status']) && $response['status'] == 'awaiting_packaging') $status = 1;
        if (!empty($response['status']) && $response['status'] == 'awaiting_deliver') $status = 2;
        if (!empty($response['status']) && $response['status'] == 'delivering') $status = 3;
        if (!empty($response['status']) && $response['status'] == 'delivered') $status = 4;
        if (!empty($response['status']) && $response['status'] == 'cancelled') $status = 5;

        return new self($market_order_id, $offer_id, $price, $quantity, $region, $warehouse, $created, $status);
    }

}
