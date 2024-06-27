<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'color_id', 'product_id', 'store_id'];


    public function product()
    {
        return $this->belongsToMany(Product::class, 'size_colors_pivot')->withPivot('store_id','store_quantity','product_quantity', 'product_id', 'size_id');
    }

    public function store()
    {
        return $this->belongsToMany(Product::class, 'size_colors_pivot')->withPivot('color_id','store_quantity', 'product_id', 'store_id', 'size_id');
    }
}
