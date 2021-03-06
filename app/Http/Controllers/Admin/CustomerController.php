<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Http\Resources\Admin\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $item = QueryBuilder::for(Customer::class)
            ->with('admin')
            ->allowedFilters(['name', 'phone'])
            ->orderByDesc('id')
            ->paginate($this->perPage);
        return json_response(CustomerResource::collection($item)->response()->getData());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'   => 'nullable|string',
            'status' => 'required|in:0,1',
            'phone'  => 'unique:customers,phone'
        ]);
        $this->user()->customer()->create($data);
        return json_response();
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return JsonResponse
     */
    public function show(Customer $customer): JsonResponse
    {
        return json_response(CustomerResource::make($customer)->additional(['type' => 1]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'items'  => 'required|array',
            'status' => 'required|in:0,1'
        ]);
        Customer::whereIn('id', $data['items'])->update(['status' => $data['status']]);
        return json_response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @return JsonResponse
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $data = $request->validate([
            'name'   => 'nullable|string',
            'phone'  => 'required|string',
            'status' => 'required|in:0,1'
        ]);
        $customer->update($data);
        return json_response();
    }
}
