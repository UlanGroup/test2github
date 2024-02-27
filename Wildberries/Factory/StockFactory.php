<?php

namespace app\Wildberries\Factory;

use app\Log\Entity\Error;
use app\Wildberries\DTO\StockDTO;
use app\Wildberries\Entity\Stock;


class StockFactory
{

    // создать ордер
    public function create(StockDTO $dto): ?Stock
    {
        /** @var Stock $stock */
        $stock = Stock::find()->where(['nmId' => $dto->nmId])->one();

        if (!empty($stock)) {
            $stock->quantity = $dto->quantity;
            $stock->inWayToClient = $dto->inWayToClient;
            $stock->inWayFromClient = $dto->inWayFromClient;
            $stock->quantityFull = $dto->quantityFull;
            $stock->Price = $dto->Price;
            $stock->Discount = $dto->Discount;
            $stock->save();
            return $stock;
        }

        $stock = new Stock();

        $stock->lastChangeDate = $dto->lastChangeDate;
        $stock->warehouseName = $dto->warehouseName;
        $stock->supplierArticle = $dto->supplierArticle;
        $stock->nmId = $dto->nmId;
        $stock->barcode = $dto->barcode;
        $stock->quantity = $dto->quantity;
        $stock->inWayToClient = $dto->inWayToClient;
        $stock->inWayFromClient = $dto->inWayFromClient;
        $stock->quantityFull = $dto->quantityFull;
        $stock->category = $dto->category;
        $stock->subject = $dto->subject;
        $stock->brand = $dto->brand;
        $stock->techSize = $dto->techSize;
        $stock->Price = $dto->Price;
        $stock->Discount = $dto->Discount;
        $stock->isSupply = $dto->isSupply;
        $stock->isRealization = $dto->isRealization;
        $stock->SCCode = $dto->SCCode;

        if (!$stock->save()) Error::error('$stock->create', $stock->getErrors());

        return $stock;
    }

}
