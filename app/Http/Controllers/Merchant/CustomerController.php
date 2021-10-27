<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\MainController;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends MainController
{

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $data = $request->validate(['phone' => 'required|string|min:8']);
        $customer = Customer::where('phone', 'like', '%' . $data['phone']. '%' )->first();

        $this->user()->queryLog()->create($data);
        return json_response($customer);
    }
}
