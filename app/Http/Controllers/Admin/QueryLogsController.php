<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Models\QueryLog;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryLogsController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = QueryBuilder::for(QueryLog::class)
            ->with(['merchant', 'customer'])
            ->allowedFilters([AllowedFilter::exact('merchant_id')])
            ->orderByDesc('id')
            ->paginate($this->perPage);
        return json_response($orders);
    }
}
