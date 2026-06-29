<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FamilyMemberTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return ['no', 'nama', 'peran', 'telepon', 'rsvp'];
    }

    public function array(): array
    {
        return [
            [1, 'Budi Santoso', 'Ayah Pengantin Pria', '08123456789', 'menunggu'],
            [2, 'Siti Rahayu',  'Ibu Pengantin Wanita', '08987654321', 'hadir'],
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
            'B' => 30,
            'C' => 30,
            'D' => 20,
            'E' => 15,
        ];
    }
}
