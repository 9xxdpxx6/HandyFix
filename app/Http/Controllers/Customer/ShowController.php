<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Http\Requests\Order\FilterRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;

class ShowController extends BaseController
{
    public function __invoke(Customer $customer)
    {
        return new CustomerResource($customer);
    }
}
