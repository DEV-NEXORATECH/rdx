<?php

namespace Tests\Unit;

use App\Domains\Shared\Support\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_formats_indonesian_currency_without_intl(): void
    {
        self::assertSame('Rp 1.234.567,89', Money::format(1234567.89, 2));
        self::assertSame('1.234.568', Money::plain(1234567.89));
    }

    public function test_null_amount_is_zero(): void
    {
        self::assertSame('Rp 0', Money::format(null));
    }
}
