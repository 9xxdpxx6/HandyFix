<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function products(Request $request)
    {
        $query = $request->input('query');

        return Product::where('name', 'like', '%' . $query . '%')
            ->orWhere('sku', 'like', '%' . $query . '%')
            ->limit(7)
            ->get(['id', 'name', 'sku', 'price']);
    }

    public function services(Request $request)
    {
        $query = $request->input('query');

        return Service::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->limit(7)
            ->get(['id', 'name', 'price']);
    }
}
