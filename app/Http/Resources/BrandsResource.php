<?php
// app/Http/Resources/CategoryResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BrandsResource extends JsonResource {
    public function toArray($request) {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => Storage::exists(url('/') . '/' . $this->image) ? url('/') . '/' . $this->image : url('/') . '/uploads/custom-images/default.jpg',
            'products' => $this->take_products
        ];
    }
}
