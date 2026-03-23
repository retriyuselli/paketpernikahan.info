<?php

namespace App\Enums;

enum VendorBadge: string
{
    case Terlaris  = 'terlaris';
    case TopRated  = 'top_rated';
    case Baru      = 'baru';
    case Unggulan  = 'unggulan';
    case Verified  = 'verified';

    public function label(): string
    {
        return match($this) {
            self::Terlaris => '🏆 Terlaris',
            self::TopRated => '⭐ Top Rated',
            self::Baru     => '🆕 Baru',
            self::Unggulan => '💎 Unggulan',
            self::Verified => '✅ Verified',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
