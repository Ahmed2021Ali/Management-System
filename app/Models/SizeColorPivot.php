<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeColorPivot extends Model
{
    use HasFactory;

    protected $table = 'size_colors_pivot';
    protected $fillable=['size_id','color_id','product_quantity','product_id','store_quantity','store_id'];
}
