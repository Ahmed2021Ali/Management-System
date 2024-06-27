<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{

    use HasFactory;

    protected $fillable = ['id','name', 'product_quantity','store_id' ,'size_id', 'color_id'];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'size_pivot')->withPivot('id','store_id','product_id');
    }

    public function store()
    {
        return $this->belongsToMany(Store::class, 'size_pivot')->withPivot('id','store_id');
    }

    public function colorsProduct()
    {
        return $this->belongsToMany(Color::class, 'size_colors_pivot')
            ->withPivot('store_quantity','store_id','color_id','product_quantity', 'product_id', 'size_id');
    }

    public function colorsStore()
    {
        return $this->belongsToMany(Color::class, 'size_colors_pivot')->withPivot('store_id','product_quantity','color_id','store_quantity', 'product_id', 'store_id', 'size_id');
    }

}
