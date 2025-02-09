<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('order.index');
        } else {
            return view('main.index');
        }
    }
}
