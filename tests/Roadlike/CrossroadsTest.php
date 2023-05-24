<?php

namespace App\Roadlike;

use App\Roadlike\Crossroads;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for Crossroads
 */
final class CrossroadsTest extends TestCase
{
    private Crossroads $crossroadsOne;
    private Crossroads $crossroadsTwo;
    private Crossroads $crossroadsThree;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->crossroadsOne = new Crossroads();
        $this->crossroadsTwo = new Crossroads();
        $this->crossroadsThree = new Crossroads();
    }

    /**
     * Test instansiation
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Roadlike\Crossroads", $this->crossroadsOne);
        $this->assertInstanceOf("App\Roadlike\Crossroads", $this->crossroadsTwo);
        $this->assertInstanceOf("App\Roadlike\Crossroads", $this->crossroadsThree);
    }
}