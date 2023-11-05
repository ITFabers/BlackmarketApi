<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id',
        'message',
        'link',
        'read_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
