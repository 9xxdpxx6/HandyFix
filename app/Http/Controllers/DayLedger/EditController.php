<?php

namespace App\Http\Controllers\DayLedger;

use App\Models\DayLedger;
use App\Models\ServiceType;

class EditController extends BaseController
{
    public function __invoke(DayLedger $dayLedger)
    {
        $serviceTypes = ServiceType::all();

        return view('day-ledger.edit', compact('dayLedger', 'serviceTypes'));
    }

}
