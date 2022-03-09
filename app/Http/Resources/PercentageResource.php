<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Resources\GradeResource;
use App\Models\Grade;


class PercentageResource extends JsonResource
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
            'percentage_id' => $this->id,
            'product_id' => $this->product_id,
            'product' => new ProductResource(Product::find($this->product_id)),
            'grade_id' => $this->grade_id,
            'grade' => new GradeResource(Grade::find($this->grade_id)),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y')
        ];
    }
}
