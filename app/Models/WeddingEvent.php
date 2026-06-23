<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_acara',
        'tgl_acara',
        'lokasi_acara',
        'vendor_booking_id',
        'catatan',
    ];

    protected $casts = [
        'tgl_acara' => 'date',
    ];

    public static array $jenisOptions = [
        'lamaran'   => 'Lamaran',
        'pengajian' => 'Pengajian',
        'akad'      => 'Akad Nikah',
        'resepsi'   => 'Resepsi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendorBooking(): BelongsTo
    {
        return $this->belongsTo(VendorBooking::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return self::$jenisOptions[$this->jenis_acara] ?? $this->jenis_acara;
    }
}
