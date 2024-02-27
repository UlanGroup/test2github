<?php

namespace app\Ozon\DTO;

class OrderDTO
{

    public function __construct(
        public readonly ?int    $order_id,
        public readonly ?string $created_at,
        public readonly ?string $city,
        public readonly ?string $delivery_type,
        public readonly ?int    $is_premium,
        public readonly ?string $payment_type,
        public readonly ?string $region,
        public readonly ?string $warehouse_name,
        public readonly ?string $order_number,
        public readonly ?string $posting_number,
        public readonly ?string $offer_id,
        public readonly ?float  $price,
        public readonly ?int    $qty,
        public readonly ?int    $sku,
        public readonly ?float  $commission,
        public readonly ?float  $delivery_cost,
        public readonly ?float  $refund_cost,
        public readonly ?int    $status
    )
    {
    }


    // создать DTO
    public static function ozon(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $order_id = null;
        $city = null;
        $delivery_type = null;
        $is_premium = 0;
        $payment_type = null;
        $region = null;
        $warehouse_name = null;
        $order_number = null;
        $posting_number = null;
        $offer_id = null;
        $price = null;
        $qty = null;
        $sku = null;
        $commission = null;
        $delivery_cost = null;
        $refund_cost = null;
        $status = null;

        if (!empty($response['order_id'])) $order_id = $response['order_id'];
        if (!empty($response['created_at'])) $created_at = date('Y-m-d H:i:s', strtotime($response['created_at']));

        if (!empty($response['analytics_data']['city'])) $city = $response['analytics_data']['city'];
        if (!empty($response['analytics_data']['region'])) {
            $region = $response['analytics_data']['region'];
        } else {
            if (!empty($city)) $region = $city;
        }
        if (!empty($response['analytics_data']['delivery_type'])) $delivery_type = $response['analytics_data']['delivery_type'];
        if (!empty($response['analytics_data']['is_premium'])) $is_premium = 1;
        if (!empty($response['analytics_data']['payment_type_group_name'])) $payment_type = $response['analytics_data']['payment_type_group_name'];
        if (!empty($response['analytics_data']['warehouse_name'])) $warehouse_name = $response['analytics_data']['warehouse_name'];

        if (!empty($response['order_number'])) $order_number = $response['order_number'];
        if (!empty($response['posting_number'])) $posting_number = $response['posting_number'];
        if (!empty($response['order_id'])) $order_id = $response['order_id'];
        if (!empty($response['order_id'])) $order_id = $response['order_id'];

        if (!empty($response['status']) && $response['status'] == 'awaiting_packaging') $status = 1;
        if (!empty($response['status']) && $response['status'] == 'awaiting_deliver') $status = 2;
        if (!empty($response['status']) && $response['status'] == 'delivering') $status = 3;
        if (!empty($response['status']) && $response['status'] == 'delivered') $status = 4;
        if (!empty($response['status']) && $response['status'] == 'cancelled') $status = 5;

        foreach ($response['products'] as $product) {
            if (!empty($product['sku'])) $sku = $product['sku'];
            if (!empty($product['offer_id'])) $offer_id = $product['offer_id'];
            if (!empty($product['price'])) $price = $product['price'];
            if (!empty($product['quantity'])) $qty = $product['quantity'];
        }

        if (!empty($response['commission'])) $commission = $response['commission'];
        if (!empty($response['delivery_cost'])) $delivery_cost = $response['delivery_cost'];
        if (!empty($response['refund_cost'])) $refund_cost = $response['refund_cost'];

        return new self($order_id, $created_at, $city, $delivery_type, $is_premium, $payment_type, $region,
            $warehouse_name, $order_number, $posting_number, $offer_id, $price, $qty, $sku,
            $commission, $delivery_cost, $refund_cost, $status);
    }

}
