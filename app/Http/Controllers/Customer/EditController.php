<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Egulias\EmailValidator\Result\Reason\CRLFAtTheEnd;
use Illuminate\Http\Request;

class EditController extends BaseController
{
    public function __invoke(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

}
