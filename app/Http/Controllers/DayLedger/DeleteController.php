<?php

namespace App\Http\Controllers\DayLedger;

use App\Models\DayLedger;

class DeleteController extends BaseController
{
    public function __invoke(DayLedger $dayLedger)
    {
        $this->service->delete($dayLedger);

        return response()->json(null, 204);
    }
}
