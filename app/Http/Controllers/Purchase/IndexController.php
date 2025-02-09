<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Order $order)
    {
        $purchases = Purchase::where('order_id', $order->id)->get();

        return view('purchase.index', compact('purchases'));
    }
}
