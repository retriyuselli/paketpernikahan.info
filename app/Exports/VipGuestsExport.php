<?php

namespace App\Exports;

use App\Models\VipGuest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VipGuestsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(
        private readonly int $userId,
    ) {}

    public function collection()
    {
        return VipGuest::where('user_id', $this->userId)
            ->orderByRaw('no IS NULL, no ASC')
            ->orderBy('id')
            ->get()
            ->map(fn (VipGuest $guest): array => [
                $guest->no,
                $guest->name,
                $guest->jabatan,
                $guest->instansi,
                $guest->phone,
                $guest->kategori,
                $guest->rsvp_status,
                $guest->catatan,
            ]);
    }

    public function headings(): array
    {
        return ['no', 'nama', 'jabatan', 'instansi', 'telepon', 'kategori', 'rsvp', 'catatan'];
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
