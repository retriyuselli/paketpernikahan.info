<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VipGuestTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return ['nama', 'jabatan', 'instansi', 'telepon', 'kategori', 'rsvp', 'catatan'];
    }

    public function array(): array
    {
        return [
            ['Budi Santoso', 'Walikota', 'Pemkot Palembang', '08123456789', 'pejabat', 'menunggu', 'Undangan kehormatan'],
            ['Ibu Rahayu',   '',         '',                  '',            'keluarga_besar', 'hadir', ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 22,
            'C' => 25,
            'D' => 18,
            'E' => 20,
            'F' => 15,
            'G' => 30,
        ];
    }
}
