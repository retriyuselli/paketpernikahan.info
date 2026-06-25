<?php

namespace App\Imports;

use App\Models\FamilyMember;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class FamilyMemberImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private int $userId;
    private int $imported = 0;
    private int $skipped  = 0;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $name = trim((string) ($row['nama'] ?? $row['name'] ?? ''));

            if (empty($name)) {
                $this->skipped++;
                continue;
            }

            FamilyMember::create([
                'user_id' => $this->userId,
                'name'    => $name,
                'role'    => trim((string) ($row['peran'] ?? $row['role'] ?? '')) ?: null,
                'phone'   => trim((string) ($row['telepon'] ?? $row['phone'] ?? '')) ?: null,
            ]);

            $this->imported++;
        }
    }

    public function getImported(): int { return $this->imported; }
    public function getSkipped(): int  { return $this->skipped; }
}
