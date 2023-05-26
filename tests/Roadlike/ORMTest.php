<?php

namespace App\Roadlike;

use App\Roadlike\ORM;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for ORM
 */
final class ORMTest extends TestCase
{
    // /**
    //  * Test random stat distribution
    //  */
    // public function testRandomStatDistribution(): void
    // {
    //     $stats = Factory::randomStatDistribution();

    //     $this->assertContainsOnly("int", $stats);
    //     $this->assertArrayHasKey("intelligence", $stats);
    //     $this->assertArrayHasKey("strength", $stats);
    //     $this->assertArrayHasKey("dexterity", $stats);
    //     $this->assertArrayHasKey("speed", $stats);
    //     $this->assertArrayHasKey("constitution", $stats);
    //     $this->assertArrayHasKey("luck", $stats);
    // }

    // /**
    //  * Test randomshape static method
    //  */
    // public function testRandomRoadShape(): void
    // {
    //     $shape = Factory::randomRoadShape();
    //     $this->assertContainsOnly("int", $shape);
    //     $this->assertArrayHasKey("length", $shape);
    //     $this->assertArrayHasKey("obstacles", $shape);
    //     $this->assertIsInt($shape["length"]);
    //     $this->assertIsInt($shape["obstacles"]);
    // }
}