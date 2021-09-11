<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Http\Requests\Merchant\OrderRequest;
use App\Http\Resources\Merchant\OrderResource;
use App\Models\Order;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class OrdersController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = QueryBuilder::for(Order::class)->orderByDesc('id')->paginate($this->perPage);
        return json_response(OrderResource::collection($orders)->response()->getData());
    }

    /**
     * @throws \Throwable
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $this->user()->order()->create($data);
            DB::commit();
        } catch (\Throwable $e) {
            \Log::error($e);
            DB::rollBack();
        }
        return json_response(status_code: 201);
    }

}
