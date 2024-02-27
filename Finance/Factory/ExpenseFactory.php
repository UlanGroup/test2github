<?php

namespace app\Finance\Factory;

use app\Finance\Entity\Expense;
use app\Log\Entity\Error;

class ExpenseFactory
{

    // создать расход
    public function create(?int $buying_id, ?int $product_id, int $project_type, int $type_id, $qty, string $cost, string $date): ?Expense
    {
        /** @var Expense $expense */
        $expense = Expense::find()->where(['buying_id' => $buying_id, 'product_id' => $product_id, 'type_id' => $type_id, 'qty' => $qty, 'cost' => $cost, 'date' => $date])->one();
        if (!empty($expense)) return $expense;

        $expense = new Expense();
        $expense->buying_id = $buying_id;
        $expense->product_id = $product_id;
        $expense->project_type = $project_type;
        $expense->type_id = $type_id;
        $expense->qty = $qty;
        $expense->cost = $cost;
        $expense->user_id = 1;
        $expense->date = $date;

        if (!$expense->save()) Error::error('$expense->create', $expense->getErrors());

        return $expense;
    }

}

