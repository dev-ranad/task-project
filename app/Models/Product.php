<?php

namespace App\Models;

use App\Http\Traits\RelationTrait;
use App\Models\Helpers\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory, RelationTrait;

    protected $fillable = [
        'name',
        'sku',
        'amazon',
        'ebay',
        'product_type',
        'quantity',
        'price'
    ];
}
