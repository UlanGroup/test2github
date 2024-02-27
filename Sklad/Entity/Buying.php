<?php

namespace app\Sklad\Entity;

use app\Finance\Entity\Expense;
use app\Ozon\Entity\Product;

/**
 * This is the model class for table "buying".
 * @property int $id                номер поставки внутри системы
 * @property int|null $supplier_id  айди поставщика
 * @property string|null $track     номер накладной
 * @property int|null $cost         суммарная стоимость товаров в поставке
 * @property int|null $weight       вес
 * @property int|null $delivery     стоимость доставки
 * @property string|null $created   дата создания
 * @property string|null $payed     дата оплаты
 * @property string|null $planned   дата доставки плановая
 * @property string|null $sended    дата отправки
 * @property string|null $arrived   дата получения
 * @property int|null $duration     часов
 * @property int|null $user_id      айди создателя
 * @property int|null $status       статус
 * @property int|null $del          удалено или нет
 *
 *
 * СТАТУСЫ
 * 1 - Создан (еще заполняю)
 * 2 - Запланирован
 * 3 - Оплачен
 * 4 - Отправлен
 * 5 - Получен
 *
 */
class Buying extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'buying';
    }


    public function rules()
    {
        return [
            [['supplier_id', 'cost', 'weight', 'delivery', 'duration', 'user_id', 'status', 'del'], 'integer'],
            [['track'], 'string', 'max' => 255],
            [['created', 'payed', 'planned', 'sended', 'arrived'], 'safe'],
        ];
    }


    // пересчитать себестоимость из товаров
    public function reCost()
    {
        $bps = BuyingProduct::find()->where(['buying_id' => $this->id])->all();
        $cost = 0;
        foreach ($bps as $item) {
            $cost += $item->price * $item->accept_qty;
        }

        $this->cost = $cost;
        $this->save();
    }


    // Установить поставщика
    public function setSupplier(string $supplier_id)
    {
        $this->supplier_id = $supplier_id;
        $this->save();
    }


    // Установить статус запланирован
    public function setPlanned()
    {
        $this->status = 2;
        $this->save();
    }


    // Установить дату оплаты и поменять статус
    public function setPayed(string $date)
    {
        $this->payed = date('Y-m-d H:i:s', strtotime($date));
        $this->status = 3;
        $this->save();
    }


    // Установить дату отправки
    public function setSended(string $date)
    {
        $this->sended = date('Y-m-d H:i:s', strtotime($date));
        $this->status = 4;
        $this->save();
    }


    // Установить дату приемки
    public function setArrived(string $date)
    {
        $this->arrived = date('Y-m-d H:i:s', strtotime($date));
        $this->status = 5;
        $this->save();
    }


    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
    }


    public function getBuyingProducts()
    {
        return $this->hasMany(BuyingProduct::class, ['buying_id' => 'id']);
    }


    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable('buying_product', ['buying_id' => 'id']);
    }


    public function getExpenses()
    {
        return $this->hasMany(Expense::class, ['buying_id' => 'id']);
    }


}