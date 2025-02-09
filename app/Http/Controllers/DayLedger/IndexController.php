<?php

namespace App\Http\Controllers\DayLedger;

use App\Http\Filters\DayLedgerFilter;
use App\Http\Requests\DayLedger\FilterRequest;
use App\Http\Resources\DayLedger\DayLedgerResource;
use App\Models\DayLedger;
use App\Models\ServiceType;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();

        $startDate = $data['start_date'] ?? '1900-01-01';
        $endDate = $data['end_date'] ?? now()->addDay()->format('Y-m-d');
        $data['dates'] = $startDate . '_' . $endDate;
        $data['sort'] = $data['sort'] ?? 'date_desc';

        $filter = app()->make(DayLedgerFilter::class, ['queryParams' => array_filter($data)]);
        $query = DayLedger::filter($filter);

        $dayLedgers = $query->paginate(30);

        $balance = $query->sum('balance');
        $total = $query->sum('total');

        $links = [
            'first' => $dayLedgers->url(1),
            'last' => $dayLedgers->url($dayLedgers->lastPage()),
            'prev' => $dayLedgers->previousPageUrl(),
            'next' => $dayLedgers->nextPageUrl(),
        ];

        $meta = [
            'current_page' => $dayLedgers->currentPage(),
            'from' => $dayLedgers->firstItem(),
            'last_page' => $dayLedgers->lastPage(),
            'links' => $dayLedgers->linkCollection(),
            'path' => $dayLedgers->path(),
            'per_page' => $dayLedgers->perPage(),
            'to' => $dayLedgers->lastItem(),
            'total' => $dayLedgers->total(),
        ];

        return response()->json([
            'data' => DayLedgerResource::collection($dayLedgers),
            'general_balance' => $balance,
            'general_total' => $total,
            'links' => $links,
            'meta' => $meta,
        ]);
    }
}
