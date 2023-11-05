<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantItem extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function variant(){
        return $this->belongsTo(ProductVariant::class,'product_variant_id');
    }
    public function getInAttrs($id)
    {
      $attrs = [];
      $data = ProductAttribute::where('product_id', $id)->get();
      foreach ($data as $key => $value) {
        if (in_array($this->id, json_decode($value->variations))) {
          array_push($attrs,$value );
        }
      }
      return $attrs;
    }

}
