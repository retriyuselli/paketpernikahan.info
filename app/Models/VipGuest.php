<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VipGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'jabatan',
        'instansi',
        'phone',
        'kategori',
        'rsvp_status',
        'catatan',
    ];

    public static array $kategoriOptions = [
        'keluarga_besar'   => 'Keluarga Besar',
        'pejabat'          => 'Pejabat',
        'tokoh_masyarakat' => 'Tokoh Masyarakat',
        'rekan_bisnis'     => 'Rekan Bisnis',
        'teman'            => 'Teman',
    ];

    public static array $rsvpOptions = [
        'menunggu'     => 'Menunggu',
        'hadir'        => 'Hadir',
        'tidak_hadir'  => 'Tidak Hadir',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
