<?php

namespace App\Http\Controllers\DayLedger;

use App\Http\Controllers\Controller;
use App\Models\DayLedger;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class CreateController extends BaseController
{
    public function __invoke()
    {
        $serviceTypes = ServiceType::all();

        return view('day-ledger.create', compact('serviceTypes'));
    }
}
