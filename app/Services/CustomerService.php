<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store($data)
    {
        $customer = new Customer();

        try {

            DB::beginTransaction();

            $customer = Customer::firstOrCreate($data);

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();
            abort(500);

        }

        return $customer;
    }

    public function update($data, Customer $customer)
    {
        try {

            DB::beginTransaction();

            $customer->update($data);

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();
            abort(500);

        }

        return $customer;
    }

    public function delete(Customer $customer)
    {
        try {

            DB::beginTransaction();

            foreach ($customer->orders as $order) {
                $this->orderService->delete($order);
            }

            $customer->delete();

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();
            abort(500);

        }
    }
}
