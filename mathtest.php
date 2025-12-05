<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Math;

final class MathTest extends TestCase
{
    public function testAdd(): void
    {
        $this->assertEquals(5, Math::add(2, 3));
    }

    public function testMultiply(): void
    {
        $this->assertEquals(20, Math::multiply(4, 5));
    }
}
