<?php

namespace Tests\Unit;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function test_total_capital_is_the_sum_of_all_capital_components(): void
    {
        $order = new Order(['capital_door' => 100, 'capital_of' => 200, 'capital_ops' => 50, 'capital_other' => 25]);

        self::assertSame(375.0, $order->total_capital);
    }
}
