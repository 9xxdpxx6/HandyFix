<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Service\CustomerService;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }
}
