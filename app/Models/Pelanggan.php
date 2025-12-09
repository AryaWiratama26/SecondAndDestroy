<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManualEncryption;

class Pelanggan extends Model
{
    use HasFactory, ManualEncryption;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'wa',
        'alamat',
        'jenis_barang',
        'total_belanja',
    ];

    
    protected function casts(): array
    {
        return [
            'total_belanja' => 'decimal:2',
        ];
    }

    
    public function setWaAttribute($value)
    {
        $this->attributes['wa'] = $this->encryptData($value);
    }

    
    public function getWaAttribute($value)
    {
        return $this->decryptData($value);
    }

    
    public function setAlamatAttribute($value)
    {
        $this->attributes['alamat'] = $this->encryptData($value);
    }

    
    public function getAlamatAttribute($value)
    {
        return $this->decryptData($value);
    }
}


