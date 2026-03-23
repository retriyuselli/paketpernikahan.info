<?php

namespace App\Enums;

enum VendorPromo: string
{
    case Diskon           = 'diskon';
    case GratisKonsultasi = 'gratis_konsultasi';
    case PaketHemat       = 'paket_hemat';
    case EarlyBird        = 'early_bird';
    case FlashSale        = 'flash_sale';
    case BonusGift        = 'bonus_gift';
    case Cicilan          = 'cicilan_0';
    case Cashback         = 'cashback';

    public function label(): string
    {
        return match($this) {
            self::Diskon           => '🏷️ Diskon',
            self::GratisKonsultasi => '💬 Gratis Konsultasi',
            self::PaketHemat       => '📦 Paket Hemat',
            self::EarlyBird        => '🐣 Early Bird',
            self::FlashSale        => '⚡ Flash Sale',
            self::BonusGift        => '🎁 Bonus Gift',
            self::Cicilan          => '💳 Cicilan 0%',
            self::Cashback         => '💰 Cashback',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
