<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Http\Requests\Order\FilterRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Support\Collection;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();

        $startDate = $data['start_date'] ?? '1900-01-01';
        $endDate = $data['end_date'] ?? now()->addDay()->format('Y-m-d');
        $data['dates'] = $startDate . '_' . $endDate;
        $data['sort'] = $data['sort'] ?? 'date_desc';

        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        $orders = Order::filter($filter)->paginate(30);

        return OrderResource::collection($orders);
    }
}
