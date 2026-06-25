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
        return ['no', 'nama', 'jabatan', 'instansi', 'telepon', 'kategori', 'rsvp', 'catatan'];
    }

    public function array(): array
    {
        return [
            [1, 'Budi Santoso', 'Walikota', 'Pemkot Palembang', '08123456789', 'pejabat', 'menunggu', 'Undangan kehormatan'],
            [2, 'Ibu Rahayu',   '',         '',                  '',            'keluarga_besar', 'hadir', ''],
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
            'A' => 6,
            'B' => 25,
            'C' => 22,
            'D' => 25,
            'E' => 18,
            'F' => 20,
            'G' => 15,
            'H' => 30,
        ];
    }
}
