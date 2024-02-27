<?php

namespace app\Wildberries\Entity;

use Yii;

/**
 * This is the model class for table "wb_order".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $lastChangeDate
 * @property string|null $warehouseName
 * @property string|null $countryName
 * @property string|null $oblastOkrugName
 * @property string|null $regionName
 * @property string|null $supplierArticle
 * @property int|null $nmId
 * @property string|null $barcode
 * @property string|null $category
 * @property string|null $subject
 * @property string|null $brand
 * @property string|null $techSize
 * @property int|null $incomeID
 * @property int|null $isSupply
 * @property int|null $isRealization
 * @property float|null $totalPrice
 * @property int|null $discountPercent
 * @property int|null $spp
 * @property float|null $forPay
 * @property float|null $finishedPrice
 * @property float|null $priceWithDisc
 * @property string|null $saleID
 * @property string|null $orderType
 * @property int|null $sticker
 * @property int|null $gNumber
 * @property string|null $srid
 * @property int|null $status
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wb_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'lastChangeDate'], 'safe'],
            [['nmId', 'incomeID', 'isSupply', 'isRealization', 'discountPercent', 'spp', 'sticker', 'gNumber', 'status'], 'integer'],
            [['totalPrice', 'forPay', 'finishedPrice', 'priceWithDisc'], 'number'],
            [['warehouseName', 'countryName', 'oblastOkrugName', 'regionName', 'supplierArticle', 'barcode', 'category', 'subject', 'brand', 'techSize', 'orderType', 'srid', 'saleID'], 'string', 'max' => 255],
        ];
    }
}
