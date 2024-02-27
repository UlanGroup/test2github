<?php

namespace app\Wildberries\Entity;

/**
 * This is the model class for table "wb_stock".
 *
 * @property int $id
 * @property string|null $lastChangeDate
 * @property string|null $warehouseName
 * @property string|null $supplierArticle
 * @property int|null $nmId
 * @property string|null $barcode
 * @property int|null $quantity
 * @property int|null $inWayToClient
 * @property int|null $inWayFromClient
 * @property int|null $quantityFull
 * @property string|null $category
 * @property string|null $subject
 * @property string|null $brand
 * @property string|null $techSize
 * @property float|null $Price
 * @property float|null $Discount
 * @property int|null $isSupply
 * @property int|null $isRealization
 * @property string|null $SCCode
 */
class Stock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wb_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lastChangeDate'], 'safe'],
            [['nmId', 'quantity', 'inWayToClient', 'inWayFromClient', 'quantityFull', 'isSupply', 'isRealization'], 'integer'],
            [['Price', 'Discount'], 'number'],
            [['warehouseName', 'supplierArticle', 'barcode', 'category', 'subject', 'brand', 'techSize', 'SCCode'], 'string', 'max' => 255],
        ];
    }
}
