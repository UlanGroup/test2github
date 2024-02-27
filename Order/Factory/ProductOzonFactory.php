<?php

namespace app\Order\Factory;

use app\Log\Entity\Error;
use app\Order\DTO\ProductDTO;
use app\Order\Entity\ProductOzon;

class ProductOzonFactory
{

    // создать товар
    public function create(ProductDTO $dto): ?ProductOzon
    {
        /** @var ProductOzon $product */
        $product = ProductOzon::find()->where(['id' => $dto->id])->one();

        if (empty($product)) {
            $product = new ProductOzon();
            if (!empty($dto->id)) $product->id = $dto->id;
            if (!empty($dto->offer_id)) $product->offer_id = $dto->offer_id;
            if (!empty($dto->sku)) $product->sku = $dto->sku;
        }

        $product->name = $dto->name;
        $product->price = $dto->price;
        if (!empty($dto->status)) $product->status = $dto->status;
        if (!$product->save()) Error::error('$product->create', $product->getErrors());

        return $product;
    }
}
