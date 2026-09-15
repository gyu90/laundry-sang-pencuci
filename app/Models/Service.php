<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     *
     * service_name = nama layanan
     * is_active    = status layanan aktif atau tidak
     */
protected $fillable = [
    'service_name',
    'image',
    'is_active',
];

    /**
     * Mengubah nilai is_active menjadi boolean.
     *
     * Contoh:
     * 1     → true
     * 0     → false
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Satu layanan dapat memiliki banyak paket.
     *
     * Contoh:
     * Laundry Kiloan
     * ├── Cuci Lipat
     * └── Cuci Setrika
     */
    public function servicePackages()
    {
        return $this->hasMany(ServicePackage::class);
    }
}