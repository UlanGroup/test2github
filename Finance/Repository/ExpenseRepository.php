<?php

namespace app\Finance\Repository;

use app\Finance\Entity\Expense;


class ExpenseRepository
{

    // Все расходы
    public function list(bool $asArray = false, $limit = 10000): ?array
    {
        $q = Expense::find()->select(['expense.id', 'expense_type.name', 'expense.cost', 'expense.date'])->join('LEFT JOIN', 'expense_type', 'expense_type.id = expense.type_id');

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('expense.date DESC')->limit($limit)->all();
    }


    // Один ордер
    public function one(int $id): ?array
    {
        return Expense::find()->where(['id' => $id])->one();
    }
}
