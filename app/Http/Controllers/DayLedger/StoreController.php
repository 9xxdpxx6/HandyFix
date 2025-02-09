<?php

namespace App\Http\Controllers\DayLedger;

use App\Http\Controllers\Controller;
use App\Http\Requests\DayLedger\StoreRequest;
use App\Http\Resources\DayLedger\DayLedgerResource;
use App\Models\DayLedger;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $dayLedger = $this->service->store($data);
        $dayLedger = new DayLedgerResource($dayLedger);

        return response([
            'message' => 'DayLedger created successfully',
            'day_ledger' => $dayLedger,
        ], 201);
    }
}
