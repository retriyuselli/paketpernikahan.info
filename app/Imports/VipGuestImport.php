<?php

namespace App\Imports;

use App\Models\VipGuest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithLimit;

class VipGuestImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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
        $validKategori = array_keys(VipGuest::$kategoriOptions);
        $validRsvp     = array_keys(VipGuest::$rsvpOptions);

        foreach ($rows as $row) {
            $name = trim((string) ($row['nama'] ?? $row['name'] ?? ''));

            if (empty($name)) {
                $this->skipped++;
                continue;
            }

            $rawKategori = Str::lower(trim((string) ($row['kategori'] ?? 'teman')));
            $kategori    = in_array($rawKategori, $validKategori) ? $rawKategori : 'teman';

            $rawRsvp  = Str::lower(trim((string) ($row['rsvp'] ?? $row['rsvp_status'] ?? 'menunggu')));
            $rsvp     = in_array($rawRsvp, $validRsvp) ? $rawRsvp : 'menunggu';

            $rawNo = $row['no'] ?? $row['no_'] ?? null;
            $no    = ($rawNo !== null && is_numeric($rawNo)) ? (int) $rawNo : null;

            VipGuest::create([
                'user_id'     => $this->userId,
                'no'          => $no,
                'name'        => $name,
                'jabatan'     => trim((string) ($row['jabatan'] ?? '')) ?: null,
                'instansi'    => trim((string) ($row['instansi'] ?? '')) ?: null,
                'phone'       => trim((string) ($row['telepon'] ?? $row['phone'] ?? '')) ?: null,
                'kategori'    => $kategori,
                'rsvp_status' => $rsvp,
                'catatan'     => trim((string) ($row['catatan'] ?? '')) ?: null,
            ]);

            $this->imported++;
        }
    }

    public function getImported(): int { return $this->imported; }
    public function getSkipped(): int  { return $this->skipped; }
}
