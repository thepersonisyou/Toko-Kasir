<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = ['nama_product', 'img', 'stok', 'harga'];

    // scopeFilter
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where('nama_product', 'like', '%' . $filters['search'] . '%')
                ->orWhere('stok', 'like', '%' . $filters['search'] . '%')
                ->orWhere('harga', 'like', '%' . $filters['search'] . '%');
        }
    }
}
