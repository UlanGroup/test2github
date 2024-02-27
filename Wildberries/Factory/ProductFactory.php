<?php

namespace app\Wildberries\Factory;

use app\Log\Entity\Error;
use app\Wildberries\DTO\ProductDTO;
use app\Wildberries\Entity\Product;


class ProductFactory
{

    // создать ордер
    public function create(ProductDTO $dto): ?Product
    {
        /** @var Product $product */
        $product = Product::find()->where(['nmID' => $dto->nmID])->one();

        if (!empty($product)) {
            $product->status = $dto->status;
            $product->save();
            return $product;
        }

        $product = new Product();

        $product->nmID = $dto->nmID;
        $product->imtID = $dto->imtID;
        $product->nmUUID = $dto->nmUUID;
        $product->subjectID = $dto->subjectID;
        $product->subjectName = $dto->subjectName;
        $product->vendorCode = $dto->vendorCode;
        $product->brand = $dto->brand;
        $product->title = $dto->title;
        $product->description = $dto->description;
        $product->video = $dto->video;
        $product->photos = $dto->photos;
        $product->dimensions = $dto->dimensions;
        $product->characteristics = $dto->characteristics;
        $product->sizes = $dto->sizes;
        $product->createdAt = $dto->createdAt;
        $product->updatedAt = $dto->updatedAt;

        $product->status = $dto->status;

        if (!$product->save()) Error::error('$product->create', $product->getErrors());

        return $product;
    }

}
