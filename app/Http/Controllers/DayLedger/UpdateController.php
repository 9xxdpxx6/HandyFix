<?php

namespace App\Http\Controllers\DayLedger;

use App\Http\Filters\DayLedgerFilter;
use App\Http\Requests\DayLedger\UpdateRequest;
use App\Http\Resources\DayLedger\DayLedgerResource;
use App\Models\DayLedger;
use App\Models\ServiceType;
use Carbon\Carbon;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, DayLedger $dayLedger)
    {
        $data = $request->validated();

        $dayLedger = $this->service->update($data, $dayLedger);
        $dayLedger = new DayLedgerResource($dayLedger);

        return response([
            'message' => 'DayLedger updated successfully',
            'day_ledger' => $dayLedger,
        ], 200);
    }
}
