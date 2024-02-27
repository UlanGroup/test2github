<?php

namespace app\Order\Factory;

use app\Order\DTO\ProductDTO;
use app\Order\Entity\Product;

class ProductFactory
{

    // создать товар
    public function create(ProductDTO $dto): ?Product
    {
        /** @var Product $product */
        $product = Product::find()->where(['id' => $dto->id])->one();
        if (!empty($product)) {
            $product->status = $dto->status;
            $product->save();
            return $product;
        }

        $product = new Product();
        $product->id = $dto->id;
        $product->offer_id = $dto->offer_id;
        $product->price = $dto->price;
        $product->status = $dto->status;
        $product->save();

        return $product;
    }
}
