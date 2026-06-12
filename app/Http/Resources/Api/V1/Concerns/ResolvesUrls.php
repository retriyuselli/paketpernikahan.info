<?php

namespace App\Http\Resources\Api\V1\Concerns;

use Illuminate\Support\Facades\Storage;

trait ResolvesUrls
{
    /**
     * Ubah path storage relatif menjadi URL absolut agar bisa
     * langsung dipakai oleh aplikasi mobile (AsyncImage, dll).
     */
    protected function absoluteUrl(?string $pathOrUrl): ?string
    {
        if (!$pathOrUrl) {
            return null;
        }

        if (str_starts_with($pathOrUrl, 'http://') || str_starts_with($pathOrUrl, 'https://')) {
            return $pathOrUrl;
        }

        if (str_starts_with($pathOrUrl, '/')) {
            return url($pathOrUrl);
        }

        return url(Storage::url($pathOrUrl));
    }

    /**
     * Ambil entri pertama dari kolom gambar yang bisa berupa
     * string tunggal maupun array JSON, lalu jadikan URL absolut.
     */
    protected function firstImageUrl(mixed $value): ?string
    {
        $path = is_array($value) ? ($value[0] ?? null) : $value;

        return $this->absoluteUrl($path);
    }
}
