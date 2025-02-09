<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Status;
use Illuminate\Http\Request;

class CreateController extends BaseController
{
    public function __invoke(Customer $customer)
    {
        // Получаем всех покупателей, кроме переданного
        $customers = Customer::where('id', '!=', $customer->id)->get();
        $statuses = Status::all();

        return view('order.create', compact('customer', 'customers', 'statuses'));
    }
}
