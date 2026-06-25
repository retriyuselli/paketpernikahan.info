<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabelPersiapan extends Model
{
    protected $fillable = ['jenis_acara', 'nama', 'sort_order'];

    public static array $jenisOptions = [
        'lamaran'   => 'Lamaran',
        'pengajian' => 'Pengajian',
        'akad'      => 'Akad Nikah',
        'resepsi'   => 'Resepsi',
    ];

    // Ambil label options per jenis_acara, siap dipakai di Select::make
    public static function optionsFor(string $jenisAcara): array
    {
        return static::where('jenis_acara', $jenisAcara)
            ->orderBy('sort_order')
            ->orderBy('nama')
            ->pluck('nama', 'nama')
            ->toArray();
    }

    public function preparationTaskTemplates(): HasMany
    {
        return $this->hasMany(PreparationTaskTemplate::class, 'label', 'nama')
            ->where('jenis_acara', $this->jenis_acara);
    }
}
