<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public function getChilds()
    {
      $childs = Category::where('parent_id',$this->id)->get();
      return $childs;
    }

    public function takeProducts() {
      return $this->belongsToMany(Product::class, 'product_subcategories', 'categories_ids', 'product_id')->oldest()->limit(10);
    }
}
