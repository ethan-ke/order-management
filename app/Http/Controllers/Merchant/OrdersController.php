<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\MainController;
use App\Http\Requests\Merchant\OrderRequest;
use App\Http\Resources\Merchant\OrderResource;
use App\Models\Order;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $queryBuilder = QueryBuilder::for($this->user()->order())->where('status', 3);
        $orders = $queryBuilder->whereBetween('created_at', [$dateS, $dateE])
            ->orderByDesc('id')->get();
        $today_income = $queryBuilder->whereDate('created_at', Carbon::now()->toDateString())->sum('commission');

        $total_amount = $orders->sum('price');
        $monthly_income = $orders->sum('commission');

        $result = [
            'orders'         => OrderResource::collection(QueryBuilder::for($this->user()->order())->whereBetween('created_at', [$dateS, $dateE])->orderByDesc('id')->get()),
            'today_income'   => sprintf("%.2f", $today_income),
            'monthly_income' => sprintf("%.2f", $monthly_income),
            'total_amount'   => sprintf("%.2f", $total_amount),
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
            $data['commission'] = $data['price'] * $this->user()->commission_rate;
            $data['commission_rate'] = $this->user()->commission_rate;
            $this->user()->order()->create($data);
            DB::commit();
        } catch (\Throwable $e) {
            \Log::error($e);
            DB::rollBack();
        }
        return json_response(status_code: 201);
    }

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        return json_response($order);
    }

    /**
     * @param OrderRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(OrderRequest $request, Order $order): JsonResponse
    {
        $data = $request->validated();
        $data['commission'] = $data['price'] * $this->user()->commission_rate;
        $data['commission_rate'] = $this->user()->commission_rate;
        $order->update($data);
        return json_response();
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => 'integer|nullable'
        ]);
        $order->update($data);
        return json_response();
    }
}
