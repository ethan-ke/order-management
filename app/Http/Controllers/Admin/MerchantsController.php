<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Http\Requests\Admin\MerchantRequest;
use App\Models\Merchant;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class MerchantsController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $coupon = QueryBuilder::for(Merchant::class)
            ->orderByDesc('id')
            ->allowedFilters(['merchant_name'])
            ->paginate($this->perPage);
        return json_response($coupon);
    }

    /**
     * Display the specified resource.
     *
     * @param Merchant $merchant
     * @return JsonResponse
     */
    public function show(Merchant $merchant): JsonResponse
    {
        return json_response($merchant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MerchantRequest $request
     * @param Merchant $merchant
     * @return JsonResponse
     */
    public function update(MerchantRequest $request, Merchant $merchant): JsonResponse
    {
        $data = $request->validated();
        $merchant->update($data);
        return json_response(null, '103');
    }
}
