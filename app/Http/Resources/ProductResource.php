<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'image' => $this->image,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'condition' => $this->condition,
            'status' => $this->status,

            // Colors (as array of color strings)
            'colors' => $this->colors ? $this->colors->pluck('color') : [],


            'vendor' => $this->vendor ? [
                'id' => $this->vendor->id,
                'business_name' => $this->vendor->business_name
            ] : null,

            // Category info
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name
            ] : null,
            
        ];
    }
}
