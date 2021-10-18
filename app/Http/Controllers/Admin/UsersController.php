<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Class UsersController
 * @package App\Http\Controllers\Merchant
 */
class UsersController extends MainController
{
    /**
     * @return JsonResponse
     */
    public function mine(): JsonResponse
    {
        $user = $this->user();
        return json_response($user);
    }

    /**
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        $merchants = Merchant::orderByDesc('id')->get();
        $item = [];
        foreach ($merchants as $key => $merchant) {
            $queryBuilder = $merchant->order()->where('status', 3);
            $lastMonthS = Carbon::now()->startOfMonth()->subMonth();
            $dateS = Carbon::now()->startOfMonth();
            $dateE = Carbon::now()->endOfMonth();
            $orders = $queryBuilder->whereBetween('created_at', [$dateS, $dateE])->orderByDesc('id')->get();
            $today_income = $queryBuilder->whereDate('created_at', Carbon::now()->toDateString())->sum('commission');
            $monthly_income = $orders->sum('commission');
            $total_amount = $orders->sum('price');

            $lastOrders = $merchant->order()->where('status', 3)->whereBetween('created_at', [$lastMonthS, Carbon::now()->startOfMonth()->subMonth()->endOfMonth()])->orderByDesc('id')->get();
            $last_month_income = $lastOrders->sum('commission');
            $last_month_total_amount = $lastOrders->sum('price');

            $item[$key] = [
                'name'                    => $merchant->username,
                'today_income'            => sprintf("%.2f", $today_income),
                'monthly_income'          => sprintf("%.2f", $monthly_income),
                'total_amount'            => sprintf("%.2f", $total_amount),
                'last_month_income'       => sprintf("%.2f", $last_month_income),
                'last_month_total_amount' => sprintf("%.2f", $last_month_total_amount),
            ];
        }
        return json_response($item);
    }
}
