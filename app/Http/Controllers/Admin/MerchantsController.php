<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Http\Requests\Admin\MerchantCreateRequest;
use App\Http\Requests\Admin\MerchantRequest;
use App\Models\Merchant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
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
            ->allowedFilters(['username'])
            ->paginate($this->perPage);
        return json_response($coupon);
    }

    /**
     * @param MerchantCreateRequest $request
     * @return JsonResponse
     */
    public function store(MerchantCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        Merchant::create($data);
        return json_response(status_code: 201);
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
        $data = $request->only('commission_rate');
        $merchant->update($data);
        return json_response();
    }
}
