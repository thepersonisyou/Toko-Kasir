<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan'; // pastikan nama tabel sesuai
    protected $fillable = ['nama_pelanggan', 'alamat', 'no_telp'];

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where('nama_pelanggan', 'like', '%' . $filters['search'] . '%')
                ->orWhere('alamat', 'like', '%' . $filters['search'] . '%')
                ->orWhere('no_telp', 'like', '%' . $filters['search'] . '%');
        }
    }
}
