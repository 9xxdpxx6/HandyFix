<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['date'])) {
            $data['date'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['date'])->format('Y-m-d H:i:s');
        }

        $order = $this->service->store($data);
        $order = new OrderResource($order);

        return response([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }
}
