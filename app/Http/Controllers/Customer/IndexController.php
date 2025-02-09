<?php

namespace App\Http\Controllers\Customer;

use App\Http\Filters\CustomerFilter;
use App\Http\Requests\Customer\FilterRequest;
use App\Http\Resources\Customer\CustomerMinResource;
use App\Models\Customer;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();
        $data['sort'] = $data['sort'] ?? 'default';

        $filter = app()->make(CustomerFilter::class, ['queryParams' => array_filter($data)]);

        $customers = Customer::filter($filter)->paginate(30);

        return CustomerMinResource::collection($customers);
    }
}
