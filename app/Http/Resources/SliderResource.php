<?php
// app/Http/Resources/SliderResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class SliderResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'badge' => $this->badge,
            'title' => $this->title_one,
            'title_two' => $this->title_two,
            'image' => Storage::exists(url('/') . '/' . $this->image) ? url('/') . '/' . $this->image : url('/') . '/uploads/custom-images/default.jpg',
            'description' => $this->description,
            'btn_text1' => $this->btn_text1,
            'btn_text2' => $this->btn_text2,
            'btn_url1' => $this->btn_url1,
            'btn_url2' => $this->btn_url2,
            'status' => $this->status,
            'serial' => $this->serial,
            'slider_location' => $this->slider_location,
            'product_slug' => $this->product_slug
        ];
    }
}
