<?php

namespace app\Wildberries\DTO;

class OrderDTO
{

    public function __construct(
        public readonly ?string $date,
        public readonly ?string $lastChangeDate,
        public readonly ?string $warehouseName,
        public readonly ?string $countryName,
        public readonly ?string $oblastOkrugName,
        public readonly ?string $regionName,
        public readonly ?string $supplierArticle,
        public readonly ?int $nmId,
        public readonly ?string $barcode,
        public readonly ?string $category,
        public readonly ?string $subject,
        public readonly ?string $brand,
        public readonly ?string $techSize,
        public readonly ?int $incomeID,
        public readonly ?int $isSupply,
        public readonly ?int $isRealization,
        public readonly ?float $totalPrice,
        public readonly ?int $discountPercent,
        public readonly ?int $spp,
        public readonly ?float $forPay,
        public readonly ?float $finishedPrice,
        public readonly ?float $priceWithDisc,
        public readonly ?string $saleID,
        public readonly ?string $orderType,
        public readonly ?int $sticker,
        public readonly ?int $gNumber,
        public readonly ?string $srid,
        public readonly ?int $status
    )
    {
    }


    // создать DTO
    public static function wb(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $date = null;
        $lastChangeDate = null;
        $warehouseName = null;
        $countryName = null;
        $oblastOkrugName = null;
        $regionName = null;
        $supplierArticle = null;
        $nmId = null;
        $barcode = null;
        $category = null;
        $subject = null;
        $brand = null;
        $techSize = null;
        $incomeID = null;
        $isSupply = null;
        $isRealization = null;
        $totalPrice = null;
        $discountPercent = null;
        $spp = null;
        $forPay = null;
        $finishedPrice = null;
        $priceWithDisc = null;
        $saleID = null;
        $orderType = null;
        $sticker = null;
        $gNumber = null;
        $srid = null;
        $status = null;

        if (!empty($response['date'])) $date = date('Y-m-d H:i:s', strtotime($response['date']));
        if (!empty($response['lastChangeDate'])) $lastChangeDate = date('Y-m-d H:i:s', strtotime($response['lastChangeDate']));

        if (!empty($response['warehouseName'])) $warehouseName = $response['warehouseName'];
        if (!empty($response['countryName'])) $countryName = $response['countryName'];
        if (!empty($response['oblastOkrugName'])) $oblastOkrugName = $response['oblastOkrugName'];
        if (!empty($response['regionName'])) $regionName = $response['regionName'];
        if (!empty($response['supplierArticle'])) $supplierArticle = $response['supplierArticle'];
        if (!empty($response['nmId'])) $nmId = $response['nmId'];
        if (!empty($response['barcode'])) $barcode = $response['barcode'];
        if (!empty($response['category'])) $category = $response['category'];
        if (!empty($response['subject'])) $subject = $response['subject'];
        if (!empty($response['brand'])) $brand = $response['brand'];
        if (!empty($response['techSize'])) $techSize = $response['techSize'];
        if (!empty($response['incomeID'])) $incomeID = $response['incomeID'];
        if (!empty($response['isSupply'])) $isSupply = $response['isSupply'];
        if (!empty($response['isRealization'])) $isRealization = $response['isRealization'];
        if (!empty($response['totalPrice'])) $totalPrice = $response['totalPrice'];
        if (!empty($response['discountPercent'])) $discountPercent = $response['discountPercent'];
        if (!empty($response['spp'])) $spp = $response['spp'];
        if (!empty($response['forPay'])) $forPay = $response['forPay'];
        if (!empty($response['finishedPrice'])) $finishedPrice = $response['finishedPrice'];
        if (!empty($response['priceWithDisc'])) $priceWithDisc = $response['priceWithDisc'];
        if (!empty($response['saleID'])) $saleID = $response['saleID'];
        if (!empty($response['orderType'])) $orderType = $response['orderType'];
        if (!empty($response['sticker'])) $sticker = $response['sticker'];
        if (!empty($response['gNumber'])) $gNumber = (int)$response['gNumber'];
        if (!empty($response['srid'])) $srid = $response['srid'];

        $status = 1;
//        if (!empty($response['status']) && $response['status'] == 'awaiting_deliver') $status = 2;
//        if (!empty($response['status']) && $response['status'] == 'delivering') $status = 3;
//        if (!empty($response['status']) && $response['status'] == 'delivered') $status = 4;
//        if (!empty($response['status']) && $response['status'] == 'cancelled') $status = 5;

        return new self(
            $date, $lastChangeDate, $warehouseName, $countryName, $oblastOkrugName, $regionName,
            $supplierArticle, $nmId, $barcode, $category, $subject, $brand, $techSize, $incomeID, $isSupply,
            $isRealization, $totalPrice, $discountPercent, $spp, $forPay, $finishedPrice, $priceWithDisc,
            $saleID, $orderType, $sticker, $gNumber, $srid, $status);
    }

}
