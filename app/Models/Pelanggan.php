<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'wa',
        'alamat',
        'jenis_barang',
        'total_belanja',
    ];

    /**
     * Casts to automatically encrypt / decrypt certain attributes.
     * Laravel uses AES-256-CBC under the hood.
     */
    protected function casts(): array
    {
        return [
            'wa' => 'encrypted',
            'alamat' => 'encrypted',
            'total_belanja' => 'decimal:2',
        ];
    }
}


