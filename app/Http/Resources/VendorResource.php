<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_name' => $this->business_name,
            'business_address'=> $this->business_address,
            'home_address' => $this->home_address,
            'email' => $this->email,
            'street' => $this->street,
            'city' => $this->city,
            'state' =>$this->state,
            'country' => $this->country,
            'phone' => $this->phone,
            'profile_image' => $this->profile_image
            ? asset('storage'. $this->profile_image)
            : asset('default_image/vendor.png'),
            
           'vendor_category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,
        ];
    }
}
