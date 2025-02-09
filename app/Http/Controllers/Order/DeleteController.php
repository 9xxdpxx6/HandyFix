<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Order $order)
    {
        $this->service->delete($order);

        return response()->json(null, 204);
    }
}
