<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FloorRecieve extends Model
{
    protected $fillable = [
        'product_id',
        'unit',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
