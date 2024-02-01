<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductSubcategory;
use DB;
class Product extends Model
{
    use HasFactory;

    protected $appends = ['averageRating','totalSold'];

    public function getAverageRatingAttribute()
    {
        return $this->avgReview()->avg('rating') ? : '0';
    }

    public function getTotalSoldAttribute()
    {
        return $this->orderProducts()->sum('qty');
    }



    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function gallery(){
        return $this->hasMany(ProductGallery::class);
    }

    public function specifications(){
        return $this->hasMany(ProductSpecification::class);
    }



    public function variants(){
        return $this->hasMany(ProductVariant::class);
    }

    public function orderProducts(){
        return $this->hasMany(OrderProduct::class);
    }


    public function attributes(){
        return $this->hasMany(ProductAttribute::class);
    }

    public function activeVariants(){
        return $this->hasMany(ProductVariant::class)->select(['id','name','product_id']);
    }



    public function variantItems(){
        return $this->hasMany(ProductVariantItem::class);
    }



    public function getCategory(){
        $productSubcategory = ProductSubcategory::where('product_id',$this->id)->first();
        $explode = explode(',',$productSubcategory->categories_ids);
        $last_sub = end($explode);
        $cagt = Category::find($last_sub);
        return $cagt->name??'';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_subcategories', 'product_id', 'categories_ids');
    }

    public function getThumbImageAttribute($value)
    {
        if(empty($value))
            return url('/') . '/uploads/custom-images/default.jpg';
        // Check if the value already contains a full URL
        if (is_string($value) && !str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
            // Prepend the site URL to the thumb_image path
            return (string)url($value);
        }
        return $value; // Return the original value if it's already a full URL
    }



    protected $fillable = [
        'name',
        'short_name',
        'slug',
        'thumb_image',
        'brand_id',
        'sold_qty',
        'short_description',
        'long_description',
        'video_link',
        'sku',
        'seo_title',
        'seo_description',
        'price',
        'offer_price',
        'tags',
        'show_homepage',
        'is_undefine',
        'is_featured',
        'new_product',
        'is_top',
        'is_best',
        'status',
        'is_specification',
        'approve_by_admin',
        'discount_price'
    ];

}
