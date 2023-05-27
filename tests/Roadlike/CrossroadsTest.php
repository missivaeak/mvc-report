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
    private Crossroads $crossroadsNone;
    private Road $roadOne;
    private Road $roadTwo;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->roadOne = $this->createStub("App\Roadlike\Road");
        $this->roadTwo = $this->createStub("App\Roadlike\Road");

        $this->crossroadsOne = new Crossroads();
        $this->crossroadsOne->addPath($this->roadOne);

        $this->crossroadsTwo = new Crossroads();
        $this->crossroadsTwo->addPath($this->roadOne);
        $this->crossroadsTwo->addPath($this->roadTwo);

        $this->crossroadsNone = new Crossroads();

    }

    /**
     * Test instansiation
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Roadlike\Crossroads", $this->crossroadsOne);
        $this->assertInstanceOf("App\Roadlike\Crossroads", $this->crossroadsTwo);
        $this->assertInstanceOf("App\Roadlike\Crossroads", $this->crossroadsNone);
    }

    /**
     * Tests addRoad and getRoad
     */
    public function testGetAddRoad(): void
    {
        $noRoads = $this->crossroadsNone->getPaths();
        $oneRoads = $this->crossroadsOne->getPaths();
        $twoRoads = $this->crossroadsTwo->getPaths();

        $this->assertContainsOnlyInstancesOf("App\Roadlike\Road", $oneRoads);
        $this->assertContainsOnlyInstancesOf("App\Roadlike\Road", $twoRoads);

        $this->assertCount(0, $noRoads);
        $this->assertCount(1, $oneRoads);
        $this->assertCount(2, $twoRoads);
    }

    /**
     * Tests getting random road with no roads
     */
    public function testGetRandomRoadNull(): void
    {
        $road = $this->crossroadsNone->getRandomPath();

        $this->assertNull($road);
    }

    /**
     * Tests getting random road with one road
     */
    public function testGetRandomRoadOne(): void
    {
        $road = $this->crossroadsOne->getRandomPath();

        $this->assertInstanceOf("App\Roadlike\Road", $road);
        $this->assertEquals($this->roadOne, $road);
    }

    /**
     * Tests getting random road with two roads
     */
    public function testGetRandomRoadTwo(): void
    {
        $roadOneFoundFlag = false;
        $roadTwoFoundFlag = false;

        for ($i = 0; $i < 100; $i++) {
            $road = $this->crossroadsTwo->getRandomPath();
            if ($road === $this->roadOne) {
                $roadOneFoundFlag = true;
            } elseif ($road === $this->roadTwo) {
                $roadTwoFoundFlag = true;
            }
        }

        $this->assertTrue($roadOneFoundFlag);
        $this->assertTrue($roadTwoFoundFlag);
    }
}
