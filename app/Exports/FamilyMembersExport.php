<?php

namespace App\Exports;

use App\Models\FamilyMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FamilyMembersExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(
        private readonly int $userId,
    ) {}

    public function collection()
    {
        return FamilyMember::where('user_id', $this->userId)
            ->orderByRaw('no IS NULL, no ASC')
            ->orderBy('id')
            ->get()
            ->map(fn (FamilyMember $member): array => [
                $member->no,
                $member->name,
                $member->role,
                $member->phone,
                $member->rsvp_status,
                $member->rsvp_updated_by_name,
                $member->rsvp_updated_at,
            ]);
    }

    public function headings(): array
    {
        return ['no', 'nama', 'peran', 'telepon', 'rsvp', 'rsvp_updated_by_name', 'rsvp_updated_at'];
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
            'F' => 25,
            'G' => 22,
        ];
    }
}
