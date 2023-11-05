<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'variant_item_id',
        'text'
    ];
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function variantItem()
    {
        return $this->belongsTo(ProductVariantItem::class);
    }

    public function getPriceLabel() {
        return $this->variant()->first()->name . ': ' . $this->variantItem()->first()->name;
    }
}
