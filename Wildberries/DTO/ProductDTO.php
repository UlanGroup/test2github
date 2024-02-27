<?php

namespace app\Wildberries\DTO;

class ProductDTO
{

    public function __construct(
        public readonly ?int $nmID,
        public readonly ?int $imtID,
        public readonly ?string $nmUUID,
        public readonly ?int $subjectID,
        public readonly ?string $subjectName,
        public readonly ?string $vendorCode,
        public readonly ?string $brand,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $video,
        public readonly ?string $photos,
        public readonly ?string $dimensions,
        public readonly ?string $characteristics,
        public readonly ?string $sizes,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
        public readonly ?int $status,
    )
    {
    }


    // создать DTO
    public static function wb(array $response): self
    {
        date_default_timezone_set("Europe/Moscow");

        $nmID = null;
        $imtID = null;
        $nmUUID = null;
        $subjectID = null;
        $subjectName = null;
        $vendorCode = null;
        $brand = null;
        $title = null;
        $description = null;
        $video = null;
        $photos = null;
        $dimensions = null;
        $characteristics = null;
        $sizes = null;
        $createdAt = null;
        $updatedAt = null;
        $status = null;

        if (!empty($response['nmID'])) $nmID = (int)$response['nmID'];
        if (!empty($response['imtID'])) $imtID = (int)$response['imtID'];
        if (!empty($response['nmUUID'])) $nmUUID = $response['nmUUID'];
        if (!empty($response['subjectID'])) $subjectID = (int)$response['subjectID'];
        if (!empty($response['subjectName'])) $subjectName = $response['subjectName'];
        if (!empty($response['vendorCode'])) $vendorCode = $response['vendorCode'];
        if (!empty($response['brand'])) $brand = $response['brand'];
        if (!empty($response['title'])) $title = $response['title'];
        if (!empty($response['description'])) $description = null; //$response['description'];
        if (!empty($response['video'])) $video = null; //$response['video'];
        if (!empty($response['photos'])) $photos = null; //$response['photos'];
        if (!empty($response['dimensions'])) $dimensions = null; //$response['dimensions'];
        if (!empty($response['characteristics'])) $characteristics = null; //$response['characteristics'];
        if (!empty($response['sizes'])) $sizes = null; //$response['sizes'];

        if (!empty($response['createdAt'])) $createdAt = date('Y-m-d H:i:s', strtotime($response['createdAt']));
        if (!empty($response['updatedAt'])) $updatedAt = date('Y-m-d H:i:s', strtotime($response['updatedAt']));

        $status = 1;
//        if (!empty($response['status']) && $response['status'] == 'awaiting_deliver') $status = 2;
//        if (!empty($response['status']) && $response['status'] == 'delivering') $status = 3;
//        if (!empty($response['status']) && $response['status'] == 'delivered') $status = 4;
//        if (!empty($response['status']) && $response['status'] == 'cancelled') $status = 5;

        return new self($nmID,
                        $imtID,
                        $nmUUID,
                        $subjectID,
                        $subjectName,
                        $vendorCode,
                        $brand,
                        $title,
                        $description,
                        $video,
                        $photos,
                        $dimensions,
                        $characteristics,
                        $sizes,
                        $createdAt,
                        $updatedAt,
                        $status);
    }

}
