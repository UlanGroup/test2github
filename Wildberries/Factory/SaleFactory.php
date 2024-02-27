<?php

namespace app\Wildberries\Factory;

use app\Log\Entity\Error;
use app\Wildberries\DTO\SaleDTO;
use app\Wildberries\Entity\Sale;

class SaleFactory
{

    // создать ордер
    public function create(SaleDTO $dto): ?Sale
    {
        /** @var Sale $sale */
        $sale = Sale::find()->where(['saleID' => $dto->saleID])->one();

        if (!empty($sale)) {
            $sale->status = $dto->status;
            $sale->lastChangeDate = $dto->lastChangeDate;
            $sale->orderType = $dto->orderType;
            $sale->save();
            return $sale;
        }

        $sale = new Sale();

        $sale->date = $dto->date;
        $sale->lastChangeDate = $dto->lastChangeDate;
        $sale->warehouseName = $dto->warehouseName;
        $sale->countryName = $dto->countryName;
        $sale->oblastOkrugName = $dto->oblastOkrugName;
        $sale->regionName = $dto->regionName;
        $sale->supplierArticle = $dto->supplierArticle;
        $sale->nmId = $dto->nmId;
        $sale->barcode = $dto->barcode;
        $sale->category = $dto->category;
        $sale->subject = $dto->subject;
        $sale->brand = $dto->brand;
        $sale->techSize = $dto->techSize;
        $sale->incomeID = $dto->incomeID;
        $sale->isSupply = $dto->isSupply;
        $sale->isRealization = $dto->isRealization;
        $sale->totalPrice = $dto->totalPrice;
        $sale->discountPercent = $dto->discountPercent;
        $sale->spp = $dto->spp;
        $sale->forPay = $dto->forPay;
        $sale->finishedPrice = $dto->finishedPrice;
        $sale->priceWithDisc = $dto->priceWithDisc;
        $sale->saleID = $dto->saleID;
        $sale->orderType = $dto->orderType;
        $sale->sticker = $dto->sticker;
        $sale->gNumber = $dto->gNumber;
        $sale->srid = $dto->srid;
        $sale->status = $dto->status;

        if (!$sale->save()) Error::error('$sale->create', $sale->getErrors());

        return $sale;
    }

}
