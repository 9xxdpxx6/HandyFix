<?php

namespace App\Http\Controllers\DayLedger;

use App\Http\Controllers\Controller;
use App\Service\DayLedgerService;

class BaseController extends Controller
{
    public $service;

    public function __construct(DayLedgerService $service)
    {
        $this->service = $service;
    }
}
