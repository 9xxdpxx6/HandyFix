<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Customer $customer)
    {
        $data = $request->validated();

        $customer = $this->service->update($data, $customer);
        $customer = new CustomerResource($customer);

        return response([
            'message' => 'Customer updated successfully',
            'customer' => $customer,
        ], 200);
    }
}
