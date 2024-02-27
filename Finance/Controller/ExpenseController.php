<?php

namespace app\Finance\Controller;

use app\Finance\Entity\Expense;
use app\Finance\Factory\ExpenseFactory;
use app\Finance\Repository\ExpenseRepository;

use agielks\yii2\jwt\JwtBearerAuth;
use app\Ozon\Entity\Order;
use app\Sklad\Entity\Buying;
use yii\filters\Cors;
use yii\web\Controller;

class ExpenseController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
//        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
        return $behaviors;
    }


    // расходы
    public function actionAll()
    {
        $expenseR = new ExpenseRepository();
        $expenses = $expenseR->list(true);

        return ['alert' => ['msg' => 'Расходы', 'type' => 'success'], 'expenses' => $expenses];
    }


    // расходы и доходы по дням
    public function actionDaily()
    {
        $srt = '2023-01-01';

        // ЗАКУПКИ 'buying.id' => [226, 227],
        $buyings = Buying::find()->where(['buying.del' => 0, 'buying.id' => [226, 227]])->joinWith(['buyingProducts', 'buyingProducts.product', 'expenses'])->asArray()->all();

//        $day = ['buy_sum' => 0, 'buy_count' => 0, 'products' => []];
//        foreach ($buyings as $buying) {
//            $buy_sum = 0;
//            foreach ($buying['buyingProducts'] as $bp) $buy_sum += $bp['price'] * $bp['accept_qty'];
//
//            foreach ($buying['buyingProducts'] as $bp) {
//                $percent = $bp['price'] * $bp['accept_qty'] * 100 / $buy_sum;
//                $delivery = $buying['delivery'] / 100 * $percent / $bp['accept_qty'];
//
//                $one_cost = $bp['price'] + $delivery;
//                $total = $one_cost * $bp['accept_qty'];
//                $day['products'][] = ['offer_id' => $bp['product']['offer_id'], 'total' => $total, 'qty' => $bp['accept_qty'], 'price' => $bp['price'], 'delivery' => $delivery, 'cumulative_up' => 0, 'one_cost' => $one_cost];
//                $day['buy_sum'] += $total;
//                $day['buy_count'] += $bp['accept_qty'];
//                unset($bp);
//            }
//        }

        // ПРОДАЖИ
//        $orders = Order::find()->where(['status' => 4])->andWhere(['DATE(created_at)' => $srt])->all();
//
//        $updated_products = [];
//        $sells = [];
//        $day_profit = 0;
//        foreach ($day['products'] as $product) {
//            foreach ($orders as $order) {
//                if ($product['offer_id'] == $order['offer_id']) {
//                    $product['qty'] -= $order['qty'];
//                    $sell = [
//                        'offer_id' => $product['offer_id'],
//                        'qty' => $order['qty'],
//                        'buy_price' => $product['price'],
//                        'delivery' => $product['delivery'],
//                        'cumulative_up' => $product['cumulative_up'],
//                        'sell_price' => $order['price'],
//                        'profit' => $order['price'] - $product['price'] - $product['delivery'] - $product['cumulative_up'],
//                    ];
//
//                    $day_profit += $sell['profit'] * $sell['qty'];
//
//                    $sells[] = $sell;
//                    unset($sell);
//                }
//            }
//            $updated_products[] = $product;
//        }
//
//
//        return ['day_profit' => $day_profit, 'updated_products' => $updated_products, 'sells' => $sells, 'orders' => $orders, 'day' => $day];

//        // РАСХОДЫ
//        $expenses = Expense::find()->select(['expense.buying_id', 'expense_type.name', 'expense.type_id', 'expense.date', 'expense.cost'])
//            ->where(['del' => 0])->andWhere(['not', ['type_id' => [8, 9]]])->andWhere(['>', 'cost', 0])
//            ->join('LEFT JOIN', 'expense_type', 'expense_type.id = expense.type_id')
//            ->asArray()->all();

//        ->andWhere(['date' => '2023-01-01'])


        $srt = '2023-01-01';
        $end = '2023-02-01';

        $calendar = [];
        while ($srt < $end) {
            if (empty($calendar)) {
                $day = ['date' => $srt, 'buy_sum' => 0, 'buy_count' => 0, 'products' => []];
            } else {
                $day['products'] = $calendar[count($calendar) - 1]['products']; // скопируем последний день в этот день
            }

            // Общие расходы месяца разделим на дни

            // если первое число месяца
            if (date('d', strtotime($srt)) == '01') {
                // 'status' => 1 - НЕ ПВЗ
                $days_in_month = (int)date('t', strtotime($srt));
                $expenses_sum_month = Expense::find()->where(['status' => 1, 'del' => 0])->andWhere(['not', ['type_id' => [8, 9]]])->andWhere(['DATE_FORMAT(date, "%Y-%m")' => date('Y-m', strtotime($srt))])->sum('cost');
                $expend_per_day = $expenses_sum_month / $days_in_month;
            }

            // заполним дату в новом дне
            $day['date'] = $srt;
            $day['expenses_sum_month'] = $expenses_sum_month;
            $day['expend_per_day'] = round($expend_per_day);
            $day_products = $day['products'];
            unset($day['products']);

            // ЗАКУПКИ
            // есть ли новая закупка (минус проданные вчера)
            foreach ($buyings as $buying) {
                // если в этот день была оплачена закупка - то добавим ее в список
                if (date('Y-m-d', strtotime($buying['payed'])) == $srt) {

                    $buy_sum = 0;
                    foreach ($buying['buyingProducts'] as $bp) $buy_sum += $bp['price'] * $bp['accept_qty'];

                    foreach ($buying['buyingProducts'] as $bp) {
                        $percent = $bp['price'] * $bp['accept_qty'] * 100 / $buy_sum;
                        $delivery = $buying['delivery'] / 100 * $percent / $bp['accept_qty'];

                        $one_cost = $bp['price'] + $delivery;
                        $total = $one_cost * $bp['accept_qty'];
                        $day_products[] = ['offer_id' => $bp['product']['offer_id'], 'total' => $total, 'qty' => $bp['accept_qty'], 'price' => $bp['price'], 'delivery' => $delivery, 'cumulative_up' => 0, 'one_cost' => $one_cost];
                        $day['buy_sum'] += $total;
                        $day['buy_count'] += $bp['accept_qty'];
                        unset($bp);
                    }
                }
            }

            if (!empty($day_products)) {

                // ПРОДАЖИ
                $orders = Order::find()->where(['status' => 4])->andWhere(['DATE(created_at)' => $srt])->all();

                $updated_products = [];
                $sells = [];
                $day_profit = $day_sell_sum = 0;
                foreach ($day_products as $product) {
                    foreach ($orders as $order) {
                        if ($product['offer_id'] == $order['offer_id']) {
                            $product['qty'] -= $order['qty'];
                            $sell = [
                                'offer_id' => $product['offer_id'],
                                'qty' => $order['qty'],
                                'buy_price' => $product['price'],
                                'delivery' => $product['delivery'],
                                'cumulative_up' => $product['cumulative_up'],
                                'sell_price' => $order['price'],
                                'profit' => $order['price'] - $product['price'] - $product['delivery'] - $product['cumulative_up'],
                            ];

                            $day_sell_sum += $sell['sell_price'] * $sell['qty'];
                            $day_profit += $sell['profit'] * $sell['qty'];

                            $sells[] = $sell;
                            unset($sell);
                        }
                    }
                    $updated_products[] = $product;
                }

                $day['sell_sum'] = $day_sell_sum;
                unset($day_sell_sum);
                $day['sells'] = $sells;
                unset($sells);
                $day['profit'] = $day_profit;
                unset($day_profit);

                $sklad = $sklad2 = [];
                // процент стоимости товара в стоимости всех товаров(для расчета % от общих расходов)
                foreach ($updated_products as $p) {
                    $p['percent'] = $p['total'] * 100 / (int)$day['buy_sum'];
                    $sklad[] = $p;
                }

                // распределить общий расход на все товары
                foreach ($sklad as $item) {
                    if ((int)$item['qty'] == 0) continue;
                    $item['today_up'] = (int)($expend_per_day / 100 * $item['percent'] / $item['qty']);
                    $item['cumulative_up'] += (int)($expend_per_day / 100 * $item['percent'] / $item['qty']);
                    $item['new_cost'] = $item['one_cost'] + $item['cumulative_up'];
                    $sklad2[] = $item;
                }
            }
//
            $day['products'] = $sklad2;

            $calendar[] = $day;
            $srt = date('Y-m-d', strtotime("$srt +1 day"));
        }

//        'order_sum' => $order_sum;
//        'прибыль' => $order_sum - $expense;
//        'прибыль шт' => ($order_sum - $expense) / $count;
//        'типы' => $types;
//        'закупка' => $buying;

        return ['daily' => $calendar];

        // return ['календарь' => $calendar, 'закупки' => $buyings, 'расходы' => $expense, 'итоговый общий расход' => round($common_expense_sum), 'расход в день' => $expend_per_day];
    }


    // расходы
    public function actionData()
    {
        $data = [
            [1, 167523.00, 167523.00, 167523.00, 171953.00, 167523.00, 167523.00, 325913.00, 225046.00, 324023.00, 334023.00, 334023.00, 430025.00, 2982621.00],
            [2, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 5000.00, 47400.00, 74800.00, 64900.00, 12500.00, 204600.00],
            [3, 164000.00, 672162.66, 525095.00, 631833.52, 63890.00, 448437.00, 111700.00, 504070.00, 466803.00, 442918.00, 458571.00, 198700.00, 4688180.18],
            [4, 444447.32, 446239.77, 446796.71, 445744.01, 445897.39, 1191388.64, 197525.43, 197304.54, 201142.41, 393368.14, 333744.35, 1489046.52, 6232645.23],
            [5, 32200.00, 32745.00, 8550.00, 14232.00, 56280.00, 13950.00, 67947.00, 26065.00, 53930.00, 35130.00, 33240.00, 45669.00, 419938.00],
            [6, 7000.00, 22802.00, 14000.00, 12600.00, 43000.00, 22500.00, 36000.00, 28000.00, 44559.00, 61587.00, 55852.00, 55400.00, 403300.00],
            [7, 30686.00, 36470.00, 534672.00, 383642.86, 376536.85, 16535.74, 174526.72, 330087.40, 967794.75, 125017.80, 87730.57, 183817.05, 3247517.74],
            [8, 204605.00, 685950.00, 885993.00, 347400.00, 740400.00, 966977.00, 895600.00, 1758000.00, 1161500.00, 2764594.00, 3308770.00, 1524653.00, 15244442.00],
            [9, 230570.00, 217102.00, 207736.00, 225814.00, 225208.00, 185800.00, 240100.00, 233900.00, 261850.00, 355600.00, 362300.00, 693000.00, 3438980.00],
            [10, 21351.00, 12389.00, 3009.00, 10755.00, 4335.00, 6234.00, 10548.94, 74426.00, 12917.00, 42350.00, 26656.00, 55628.00, 280598.94],
            [11, 0.00, 0.00, 1599.00, 100.00, 0.00, 0.00, 128.00, 100.00, 370.00, 700.00, 4472.00, 3762.73, 11231.73],
            [12, 11026.25, 10804.38, 0.00, 20668.90, 9531.34, 8005.28, 7108.78, 7945.70, 8735.70, 37040.27, 36633.97, 39517.31, 197017.88],
            [13, 16105.40, 4384.71, 51000.00, 8425.00, 4350.00, 11140.00, 20051.00, 17113.00, 13300.00, 4500.00, 18918.36, 123310.00, 292597.47],
            [14, 0.00, 0.00, 0.00, 800.00, 0.00, 0.00, 0.00, 4587.00, 4977.00, 12758.00, 10000.00, 18503.00, 51625.00],
            [15, 0.00, 0.00, 447143.48, 366696.00, 0.00, 50638.25, 191320.00, 0.00, 77403.86, 533775.00, 0.00, 86331.55, 1753308.14],
            [16, 6079.00, 6149.00, 7149.00, 2649.00, 2949.00, 2799.00, 6149.00, 3570.00, 6050.00, 6382.50, 4289.00, 6510.00, 60724.50],
            [17, 2800.00, 15000.00, 300900.00, 2990.00, 0.00, 3474.00, 0.00, 0.00, 6475.00, 0.00, 5000.00, 350000.00, 686639.00],
            [18, 0.00, 6399.00, 26252.00, 9800.00, 63000.00, 0.00, 59545.00, 82208.00, 70070.00, 22000.00, 269372.00, 217792.00, 826438.00],
            [19, 83852.68, 74578.62, 74003.29, 77555.99, 74902.61, 75011.36, 65074.57, 65295.46, 620024.08, 88649.86, 209192.65, 153995.48, 1662136.65],
            [20, 476.25, 6873.00, 69064.00, 3039.75, 5270.00, 2931.00, 97928.38, 199056.50, 3178.69, 40117.50, 36642.25, 336624.50, 801201.82],
            [21, 0.00, 0.00, 0.00, 0.00, 62000.00, 70000.00, 111000.00, 60000.00, 60000.00, 80000.00, 60000.00, 0.00, 503000.00],
            [22, 0.00, 2150.00, 34019.00, 23575.00, 35696.00, 0.00, 0.00, 0.00, 8869.00, 0.00, 1062.00, 0.00, 105371.00],
            [23, 1325.00, 2000.00, 1900.00, 1851.00, 2970.00, 1320.00, 2420.00, 3920.00, 2170.00, 7474.17, 4711.18, 10983.04, 43044.39],
            [24, 0.00, 0.00, 3200.00, 0.00, 21350.00, 34400.00, 35000.00, 20250.00, 21300.00, 15000.00, 22600.00, 2558.86, 175658.86],
            [25, 0.00, 0.00, 12250.00, 0.00, 0.00, 0.00, 108537.94, 0.00, 186600.00, 0.00, 0.00, 3000.00, 310387.94],
            [26, 10790.00, 390.00, 390.00, 390.00, 390.00, 1182141.00, 263886.32, 283073.00, 706108.28, 115406.79, 1174717.32, 65265.20, 3802947.91],
            [27, 300000.00, 0.00, 0.00, 0.00, 0.00, 200000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1189195.95, 1689195.95]
        ];

        $eF = new ExpenseFactory();

        foreach ($data as $item) {
            $eF->create(1, 0, 1, $item[0], 0, $item[1], '2023-01-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[2], '2023-02-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[3], '2023-03-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[4], '2023-04-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[5], '2023-05-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[6], '2023-06-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[7], '2023-07-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[8], '2023-08-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[9], '2023-09-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[10], '2023-10-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[11], '2023-11-01');
            $eF->create(1, 0, 1, $item[0], 0, $item[12], '2023-12-01');
        }

    }
}
