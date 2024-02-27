<?php

namespace app\Wildberries\Factory;

use app\Log\Entity\Error;
use app\Wildberries\DTO\OrderDTO;
use app\Wildberries\Entity\Order;

class OrderFactory
{

    // создать ордер
    public function create(OrderDTO $dto): ?Order
    {
        /** @var Order $order */
        $order = Order::find()->where(['gNumber' => $dto->gNumber])->one();

        if (!empty($order)) {
            $order->status = $dto->status;
            $order->lastChangeDate = $dto->lastChangeDate;
            $order->orderType = $dto->orderType;
            $order->save();
            return $order;
        }

        $order = new Order();

        $order->date = $dto->date;
        $order->lastChangeDate = $dto->lastChangeDate;
        $order->warehouseName = $dto->warehouseName;
        $order->countryName = $dto->countryName;
        $order->oblastOkrugName = $dto->oblastOkrugName;
        $order->regionName = $dto->regionName;
        $order->supplierArticle = $dto->supplierArticle;
        $order->nmId = $dto->nmId;
        $order->barcode = $dto->barcode;
        $order->category = $dto->category;
        $order->subject = $dto->subject;
        $order->brand = $dto->brand;
        $order->techSize = $dto->techSize;
        $order->incomeID = $dto->incomeID;
        $order->isSupply = $dto->isSupply;
        $order->isRealization = $dto->isRealization;
        $order->totalPrice = $dto->totalPrice;
        $order->discountPercent = $dto->discountPercent;
        $order->spp = $dto->spp;
        $order->forPay = $dto->forPay;
        $order->finishedPrice = $dto->finishedPrice;
        $order->priceWithDisc = $dto->priceWithDisc;
        $order->orderType = $dto->orderType;
        $order->sticker = $dto->sticker;
        $order->gNumber = $dto->gNumber;
        $order->srid = $dto->srid;
        $order->status = $dto->status;

        if (!$order->save()) Error::error('$order->create', $order->getErrors());

        return $order;
    }

}
