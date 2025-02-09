<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class ShowController extends BaseController
{
    public function __invoke(Order $order)
    {
        return new OrderResource($order);
    }
}
