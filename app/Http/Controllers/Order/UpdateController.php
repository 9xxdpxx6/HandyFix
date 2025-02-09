<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Carbon\Carbon;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Order $order)
    {
        $data = $request->validated();
        if (!empty($data['date'])) {
            $data['date'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['date'])->format('Y-m-d H:i:s');
        }

        $order = $this->service->update($data, $order);
        $order = new OrderResource($order);

        return response([
            'message' => 'Order updated successfully',
            'order' => $order,
        ], 200);
    }
}
