<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Customer $customer)
    {
        $this->service->delete($customer);

        return response()->json(null, 204);
    }
}
