<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'id'         => $this->resource->id,
            'name'       => $this->resource->name,
            'admin_name' => $this->resource->admin?->username,
            'phone'      => $phone,
            'status'     => $this->resource->status,
            'created_at' => $this->resource->created_at->toDateTimeString(),
            'updated_at' => $this->resource->updated_at->toDateTimeString(),
        ];
    }
}
