<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function it_can_get_formatted_price()
    {
        $price = 29999;
        $this->assertSame('$299.99', presentPrice($price));
    }
}
