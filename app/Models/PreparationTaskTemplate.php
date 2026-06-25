<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreparationTaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_acara',
        'label',
        'title',
        'sort_order',
    ];

    public static array $jenisOptions = [
        'lamaran'   => 'Lamaran',
        'pengajian' => 'Pengajian',
        'akad'      => 'Akad Nikah',
        'resepsi'   => 'Resepsi',
    ];
}
