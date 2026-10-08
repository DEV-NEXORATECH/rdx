<?php

namespace App\Domains\Shared\Support;

final class Money
{
    public static function format(int|float|string|null $amount, int $decimals = 0, ?string $currency = 'IDR'): string
    {
        $value = (float) ($amount ?? 0);

        $formatted = number_format($value, $decimals, ',', '.');

        if ($currency === 'IDR') {
            return 'Rp ' . $formatted;
        }

        return $formatted . ' ' . $currency;
    }

    public static function plain(int|float|string|null $amount): string
    {
        return number_format((float) ($amount ?? 0), 0, ',', '.');
    }
}
