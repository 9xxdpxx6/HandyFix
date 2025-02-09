<?php

namespace App\Http\Controllers\DayLedger;

use App\Http\Controllers\Controller;
use App\Http\Filters\DayLedgerFilter;
use App\Http\Filters\OrderFilter;
use App\Http\Requests\Order\FilterRequest;
use App\Http\Resources\DayLedger\DayLedgerResource;
use App\Models\Customer;
use App\Models\DayLedger;
use App\Models\Order;
use App\Models\ServiceType;
use App\Models\Status;
use Illuminate\Http\Request;

class ShowController extends BaseController
{
    public function __invoke(DayLedger $dayLedger)
    {
        return new DayLedgerResource($dayLedger);
    }
}
