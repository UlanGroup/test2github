<?php

namespace app\Wildberries\Entity;

use Yii;

/**
 * This is the model class for table "wb_product".
 *
 * @property int $id
 * @property int|null $nmID
 * @property int|null $imtID
 * @property string|null $nmUUID
 * @property int|null $subjectID
 * @property string|null $subjectName
 * @property string|null $vendorCode
 * @property string|null $brand
 * @property string|null $title
 * @property string|null $description
 * @property string|null $video
 * @property string|null $photos
 * @property string|null $dimensions
 * @property string|null $characteristics
 * @property string|null $sizes
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property int|null $status
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wb_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nmID', 'imtID', 'subjectID', 'status'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['nmUUID', 'subjectName', 'vendorCode', 'brand', 'title', 'description', 'video', 'photos', 'dimensions', 'characteristics', 'sizes'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }
}
