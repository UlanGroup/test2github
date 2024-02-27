<?php

namespace app\Ozon\Service;

use app\Ozon\DTO\ProductDTO;
use app\Ozon\Entity\Product;
use app\Ozon\Factory\ProductFactory;
use app\Service\OzonApiService;

class OzonService
{

    // создать товары
    public function createProducts(bool $archived = false)
    {
        $result = OzonApiService::getProducts($archived);
        if (empty($result)) return null;

        $productF = new ProductFactory();

        foreach ($result['result']['items'] as $item) {
            $status = 1;
            if ($item['archived']) $status = 0;
            $productF->create($item['product_id'], $item['offer_id'], $status);
        }
    }


    // обновить один товар
    public static function updateProduct(Product $product)
    {
        $result = OzonApiService::getProduct($product->id);
        if (empty($result)) return null;

        $dto = ProductDTO::ozon($result['result']);
        $product->upd($dto);
    }


    // Получить данные из ozon
    public static function createOrders($date_to = false)
    {

    }


    // Получить остатки из ozon
    public static function createStock()
    {

    }


}