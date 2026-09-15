<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackage extends Model
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     *
     * service_id  = ID layanan induk
     * package_name = nama paket layanan
     * price       = harga paket
     * is_active   = status paket masih tersedia atau tidak
     */
    protected $fillable = [
        'service_id',
        'package_name',
        'price',
        'is_active',
    ];

    /**
     * Mengubah tipe data beberapa kolom secara otomatis.
     *
     * price menjadi angka desimal dengan 2 angka di belakang koma.
     * is_active menjadi boolean (true/false).
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Setiap paket hanya dimiliki oleh satu layanan.
     *
     * Contoh:
     * Laundry Kiloan
     * ├── Cuci Lipat
     * └── Cuci Setrika
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }


/**
 * Order item yang menggunakan paket ini.
 */
public function orderItems(): HasMany
{
    return $this->hasMany(OrderItem::class);
}

}