<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;
use App\Models\Status;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $customer = $this->service->store($data);
        $customer = new CustomerResource($customer);

        return response([
            'message' => 'Customer created successfully',
            'customer' => $customer,
        ], 201);
    }
}
