<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\MainController;
use App\Http\Requests\Merchant\OrderRequest;
use App\Http\Resources\Merchant\OrderResource;
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
        $dateS = Carbon::now()->startOfMonth();
        $dateE = Carbon::now()->endOfMonth();

        $query = QueryBuilder::for($this->user()->order())
            ->whereBetween('created_at',[$dateS, $dateE])
            ->orderByDesc('id');

        $items = $query->get();
//        $items = $query->paginate($this->perPage);
        $orders = $query->get();
        $result = [
            'orders' => OrderResource::collection($items),
//            'orders' => OrderResource::collection($items)->response()->getData(),
            'income' => $orders->sum('price') * $this->user()->commission_ratio
        ];
        return json_response($result);
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
