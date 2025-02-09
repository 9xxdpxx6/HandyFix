<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilterListController extends BaseController
{
    public function __invoke()
    {
        $sortOptions = [
            ['value' => 'default', 'label' => 'По умолчанию'],
            ['value' => 'date_asc', 'label' => 'Сначала старые'],
            ['value' => 'date_desc', 'label' => 'Сначала новые'],
            ['value' => 'orders_desc', 'label' => 'Больше заказов'],
            ['value' => 'orders_asc', 'label' => 'Меньше заказов'],
            ['value' => 'total_price_desc', 'label' => 'Большая общая стоимость'],
            ['value' => 'total_price_asc', 'label' => 'Меньшая общая стоимость'],
        ];

        $result = [
            'sortOptions' => $sortOptions,
        ];

        return response()->json($result);
    }
}
