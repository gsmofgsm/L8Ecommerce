<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category'; // to avoid naming conflict with Voyager

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
