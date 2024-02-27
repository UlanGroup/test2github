<?php

namespace app\Wildberries\DTO;

class StockDTO
{

    public function __construct(
        public readonly ?string $lastChangeDate,
        public readonly ?string $warehouseName,
        public readonly ?string $supplierArticle,
        public readonly ?int $nmId,
        public readonly ?string $barcode,
        public readonly ?int $quantity,
        public readonly ?int $inWayToClient,
        public readonly ?int $inWayFromClient,
        public readonly ?int $quantityFull,
        public readonly ?string $category,
        public readonly ?string $subject,
        public readonly ?string $brand,
        public readonly ?string $techSize,
        public readonly ?float $Price,
        public readonly ?float $Discount,
        public readonly ?int $isSupply,
        public readonly ?int $isRealization,
        public readonly ?string $SCCode
    )
    {
    }
    // создать DTO
    public static function wb(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $lastChangeDate = null;
        $warehouseName = null;
        $supplierArticle = null;
        $nmId = null;
        $barcode = null;
        $quantity = null;
        $inWayToClient = null;
        $inWayFromClient = null;
        $quantityFull = null;
        $category = null;
        $subject = null;
        $brand = null;
        $techSize = null;
        $Price = null;
        $Discount = null;
        $isSupply = null;
        $isRealization = null;
        $SCCode = null;

        if (!empty($response['lastChangeDate'])) $lastChangeDate = date('Y-m-d H:i:s', strtotime($response['lastChangeDate']));

        if (!empty($response['warehouseName'])) $warehouseName = $response['warehouseName'];
        if (!empty($response['supplierArticle'])) $supplierArticle = $response['supplierArticle'];
        if (!empty($response['nmId'])) $nmId = (int)$response['nmId'];
        if (!empty($response['barcode'])) $barcode = $response['barcode'];
        if (!empty($response['quantity'])) $quantity = (int)$response['quantity'];
        if (!empty($response['inWayToClient'])) $inWayToClient = (int)$response['inWayToClient'];
        if (!empty($response['inWayFromClient'])) $inWayFromClient = (int)$response['inWayFromClient'];
        if (!empty($response['quantityFull'])) $quantityFull = (int)$response['quantityFull'];
        if (!empty($response['category'])) $category = $response['category'];
        if (!empty($response['subject'])) $subject = $response['subject'];
        if (!empty($response['brand'])) $brand = $response['brand'];
        if (!empty($response['techSize'])) $techSize = $response['techSize'];
        if (!empty($response['Price'])) $Price = $response['Price'];
        if (!empty($response['Discount'])) $Discount = (int)$response['Discount'];
        if (!empty($response['isSupply'])) $isSupply = (int)$response['isSupply'];
        if (!empty($response['isRealization'])) $isRealization = (int)$response['isRealization'];
        if (!empty($response['SCCode'])) $SCCode = (int)$response['SCCode'];


        return new self(
            $lastChangeDate,
            $warehouseName,
            $supplierArticle,
            $nmId,
            $barcode,
            $quantity,
            $inWayToClient,
            $inWayFromClient,
            $quantityFull,
            $category,
            $subject,
            $brand,
            $techSize,
            $Price,
            $Discount,
            $isSupply,
            $isRealization,
            $SCCode);
    }
}
