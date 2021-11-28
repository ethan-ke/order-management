<?php

namespace App\Http\Resources\Merchant;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        if (auth()->user()->username === 'ethan') {
            $phone = $this->resource->phone;
        } else {
            $phone = substr_replace($this->resource->phone, '****', 4, 6);
        }
        return [
            'id'              => $this->resource->id,
            'merchant_name'   => $this->resource->merchant->username,
            'phone'           => $phone,
            'price'           => $this->resource->price,
            'room_number'     => $this->resource->room_number,
            'commission'      => $this->resource->commission,
            'commission_rate' => $this->resource->commission_rate,
            'deduction'       => $this->resource->deduction,
            'status'          => $this->resource->status,
            'created_at'      => $this->resource->created_at->toDateTimeString(),
            'updated_at'      => $this->resource->updated_at->toDateTimeString(),
        ];
    }
}
