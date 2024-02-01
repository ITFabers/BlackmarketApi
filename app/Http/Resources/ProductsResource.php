<?php
// app/Http/Resources/ProductsResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

class ProductsResource extends JsonResource {
    public function toArray($request) {
        $image = str_replace(url('/'), '', $this->thumb_image);
        if(!empty($resource)) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'shortName' => $this->short_name,
                'slug' => $this->slug,
                'image' => (!empty($this->thumb_image) && File::exists(public_path(str_replace(url('/'), '', $this->thumb_image)))) ? $this->thumb_image : url('/') . '/uploads/custom-images/default.jpg',
                'brand' => $this->brand_id,
                'qty' => $this->sold_qty,
                'shortDescription' => $this->short_description,
                'longDescription' => $this->long_description,
                'sku' => $this->sku,
                'seoTitle' => $this->seo_title,
                'seoDescription' => $this->seo_description,
                'price' => $this->price,
                'offerPrice' => $this->offer_price,
                'tags' => $this->tags,
                'isSpecification' => $this->is_specification,
                'discountPrice' => $this->discount_price,

                'pagination' => [
                    'total' => $this->resource['pagination']['total'],
                    'perPage' => $this->resource['pagination']['perPage'],
                    'currentPage' => $this->resource['pagination']['currentPage'],
                    'lastPage' => $this->resource['pagination']['lastPage'],
                    'prevPage' => $this->resource['pagination']['prevPage'],
                    'nextPage' => $this->resource['pagination']['nextPage'],
                ],
            ];
        } else {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'shortName' => $this->short_name,
                'slug' => $this->slug,
                'image' => (!empty($this->thumb_image) && File::exists(public_path(str_replace(url('/'), '', $this->thumb_image)))) ? $this->thumb_image : url('/') . '/uploads/custom-images/default.jpg',
                'brand' => $this->brand_id,
                'qty' => $this->sold_qty,
                'shortDescription' => $this->short_description,
                'longDescription' => $this->long_description,
                'sku' => $this->sku,
                'seoTitle' => $this->seo_title,
                'seoDescription' => $this->seo_description,
                'price' => $this->price,
                'offerPrice' => $this->offer_price,
                'tags' => $this->tags,
                'isSpecification' => $this->is_specification,
                'discountPrice' => $this->discount_price,
            ];
        }

    }
}
