<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Resources\Merchant\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
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
        $orders = QueryBuilder::for(Order::class)
            ->allowedFilters([AllowedFilter::exact('merchant_id'), 'room_number'])
            ->orderByDesc('id')
            ->paginate($this->perPage);
        return json_response(OrderResource::collection($orders)->response()->getData());
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
        $data['commission'] = $data['price'] * $order->merchant->commission_rate;
        $data['commission_rate'] = $order->merchant->commission_rate;
        $order->update($data);
        return json_response();
    }
}
