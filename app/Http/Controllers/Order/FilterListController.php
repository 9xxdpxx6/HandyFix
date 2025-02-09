<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Status\StatusResource;
use App\Models\Status;
use Illuminate\Http\Request;

class FilterListController extends BaseController
{
    public function __invoke()
    {
        $sortOptions = [
            ['value' => 'default', 'label' => 'По умолчанию'],
            ['value' => 'date_asc', 'label' => 'Сначала старые'],
            ['value' => 'date_desc', 'label' => 'Сначала новые'],
        ];

        $statuses = StatusResource::collection(Status::all());

        $result = [
            'statuses' => $statuses,
            'sortOptions' => $sortOptions,
        ];

        return response()->json($result);
    }
}
