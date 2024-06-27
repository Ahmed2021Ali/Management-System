<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['size_id', 'code', 'total_quantity', 'color_id', 'name', 'price'];

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'size_pivot')
            ->withPivot('id', 'store_id', 'product_id');

    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }
}
