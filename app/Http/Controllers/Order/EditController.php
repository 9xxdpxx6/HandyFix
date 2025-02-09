<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;

class EditController extends BaseController
{
    public function __invoke(Order $order)
    {
        $customers = Customer::all();
        $statuses = Status::all();

        return view('order.edit', compact('order', 'customers', 'statuses'));
    }
}
