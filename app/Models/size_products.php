<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class size_products extends Model
{
    use HasFactory;
    protected $fillable=['size_id','store_id','product_id'];
}
