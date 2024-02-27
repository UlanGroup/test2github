<?php

namespace app\Ozon\Entity;


use app\Log\Entity\Error;
use app\Ozon\DTO\ProductDTO;

/**
 * This is the model class for table "ozon_product".
 *
 * @property int $id
 * @property string $offer_id
 * @property int $sku
 * @property int $category_id
 * @property string $name
 * @property string $barcode
 * @property string $image
 * @property float $price
 * @property int $status
 *
 */
class Product extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'ozon_product';
    }


    public function rules()
    {
        return [
            [['id', 'offer_id'], 'required'],
            [['id', 'sku', 'category_id', 'status'], 'integer'],
            [['price'], 'number'],
            [['offer_id', 'name', 'barcode', 'image'], 'string', 'max' => 255],
        ];
    }


    // обновить товар
    public function upd(ProductDTO $dto): ?Product
    {
        if (!empty($dto->sku)) $this->sku = $dto->sku;
        if (!empty($dto->category_id)) $this->category_id = $dto->category_id;
        if (!empty($dto->name)) $this->name = $dto->name;
        if (!empty($dto->barcode)) $this->barcode = $dto->barcode;
        if (!empty($dto->image)) $this->image = $dto->image;
        if (!empty($dto->price)) $this->price = $dto->price;
        if (!empty($dto->status)) $this->status = $dto->status;
        if (!$this->save()) Error::error('$this->update', $this->getErrors());

        return $this;
    }


    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getOrders()
    {
        return $this->hasMany(Order::class, ['product_id' => 'id']);
    }

    public function getSell1()
    {
        return $this->hasMany(Order::class, ['product_id' => 'id'])->onCondition(['>', 'created_at', date('Y-m-d', strtotime("-30 days"))]);
    }

    public function getSell3()
    {
        return $this->hasMany(Order::class, ['product_id' => 'id'])->onCondition(['>', 'created_at', date('Y-m-d', strtotime("-90 days"))]);
    }

    public function getStocks()
    {
        return $this->hasMany(Stock::class, ['product_id' => 'id']);
    }

}
