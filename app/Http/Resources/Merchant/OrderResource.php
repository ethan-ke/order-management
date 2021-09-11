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
        return [
            'id'          => $this->resource->id,
            'phone'       => $this->resource->phone,
            'price'       => $this->resource->price,
            'room_number' => $this->resource->room_number,
            'status'      => $this->resource->status,
            'created_at'  => $this->resource->created_at->toDateTimeString(),
            'updated_at'  => $this->resource->updated_at->toDateTimeString(),
        ];
    }
}
