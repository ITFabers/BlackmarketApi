<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;



    public function orderProductVariants(){
        return $this->hasMany(OrderProductVariant::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

}
