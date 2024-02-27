<?php

namespace app\Ozon\Factory;

use app\Log\Entity\Error;
use app\Ozon\Entity\Product;

class ProductFactory
{

    // создать товар
    public function create(int $id, string $offer_id, bool $status): ?Product
    {
        /** @var Product $product */
        $product = Product::find()->where(['id' => $id])->one();

        if (empty($product)) {
            $product = new Product();
            $product->id = $id;
            $product->offer_id = $offer_id;
        }

        $product->status = (int)$status;
        if (!$product->save()) Error::error('$product->create', $product->getErrors());

        return $product;
    }

}
