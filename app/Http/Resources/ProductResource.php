<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SubcategoryResource;
use App\Http\Resources\BrandResource;
use App\Models\Subcategory;
use App\Models\Brand;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $baseurl = URL('/');

        return [
            'product_id' => $this->id,
            'product_code' => $this->code,
            'product_code' => $this->name,
            'product_photo' => $baseurl.$this->photo,
            'product_description' =>$this->description,
            'product_price' => $this->price,
            'product_min_qty' => $this->min_qty,
            'product_status' => $this->status,
            'subcategory_id' => $this->subcategory_id,
            'subcategory' => new SubcategoryResource(Subcategory::find($this->subcategory_id)),
            'brand_id' => $this->brand_id,
            'brand' => new BrandResource(Brand::find($this->brand_id)),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y')
        ];
    }
}
