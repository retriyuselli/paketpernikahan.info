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
        $nextNo = (FamilyMember::where('user_id', $this->userId)->max('no') ?? 0) + 1;
        $validRsvp = array_keys(FamilyMember::$rsvpOptions);

        foreach ($rows as $row) {
            $name = trim((string) ($row['nama'] ?? $row['name'] ?? ''));

            if (empty($name)) {
                $this->skipped++;
                continue;
            }

            $rawNo = $row['no'] ?? $row['no_'] ?? null;
            $no    = ($rawNo !== null && is_numeric($rawNo)) ? (int) $rawNo : $nextNo;

            $rawRsvp = trim((string) ($row['rsvp'] ?? $row['rsvp_status'] ?? 'menunggu'));
            $rsvp    = in_array($rawRsvp, $validRsvp, true) ? $rawRsvp : 'menunggu';

            FamilyMember::create([
                'user_id'     => $this->userId,
                'no'          => $no,
                'name'        => $name,
                'role'        => trim((string) ($row['peran'] ?? $row['role'] ?? '')) ?: null,
                'phone'       => trim((string) ($row['telepon'] ?? $row['phone'] ?? '')) ?: null,
                'rsvp_status' => $rsvp,
            ]);

            $nextNo = max($nextNo, $no + 1);
            $this->imported++;
        }
    }

    public function getImported(): int { return $this->imported; }
    public function getSkipped(): int  { return $this->skipped; }
}
