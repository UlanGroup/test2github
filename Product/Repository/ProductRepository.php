<?php

namespace app\Product\Repository;

use app\Oon\Entity\Product;

class ProductRepository
{

    // Все товары
    public function all(bool $asArray = false, $limit = 10000): ?array
    {
        $q = Product::find();

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('id DESC')->limit($limit)->all();
    }

}
