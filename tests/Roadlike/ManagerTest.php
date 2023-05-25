<?php

namespace App\Roadlike;

use App\Roadlike\Manager;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for Manager
 */
final class ManagerTest extends TestCase
{
    private Challenger $challenger;
    private Road $journey;
    private Crossroads $crossroads;
    private Manager $managerOne;
    private Manager $managerTwo;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->challenger = $this->createStub("App\Roadlike\Challenger");
        $this->journey = $this->createStub("App\Roadlike\Road");
        $this->crossroads = $this->createStub("App\Roadlike\Crossroads");
        $this->managerOne = new Manager($this->challenger, $this->journey);
        $this->managerTwo = new Manager($this->challenger, $this->journey, 20);
    }

    /**
     * Test instansiation
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Roadlike\Manager", $this->managerOne);
        $this->assertInstanceOf("App\Roadlike\Manager", $this->managerTwo);
    }

    /**
     * Test set and get crossroads
     */
    public function testSetGetCrossroads(): void
    {
        $before = $this->managerOne->getCrossroads();
        $this->managerOne->setCrossroads($this->crossroads);
        $after = $this->managerOne->getCrossroads();

        $this->assertNull($before);
        $this->assertEquals($this->crossroads, $after);
    }

    /**
     * Test unset crossroads
     */
    public function testUnsetCrossroads(): void
    {
        $this->managerOne->setCrossroads($this->crossroads);
        $this->managerOne->unsetCrossroads();
        $after = $this->managerOne->getCrossroads();

        $this->assertNull($after);
    }

    /**
     * Test get challenger
     */
    public function testGetChallenger(): void
    {
        $challenger = $this->managerOne->getChallenger();
        $this->assertEquals($this->challenger, $challenger);
    }

    /**
     * Test get journey
     */
    public function testGetJourney(): void
    {
        $journey = $this->managerOne->getJourney();
        $this->assertEquals($this->journey, $journey);
    }

    /**
     * Test get time and max time with default values
     */
    public function testGetTimeDefault(): void
    {
        $time = $this->managerOne->getTime();
        $startingTime = $this->managerOne->getStartingTime();
        $this->assertEquals(500, $time);
        $this->assertEquals(500, $startingTime);
    }

    /**
     * Test get time and max time
     */
    public function testGetTime(): void
    {
        $time = $this->managerTwo->getTime();
        $startingTime = $this->managerTwo->getStartingTime();
        $this->assertEquals(20, $time);
        $this->assertEquals(20, $startingTime);
    }

    /**
     * Test modify time
     */
    public function testModifyTime(): void
    {
        $this->managerOne->modifyTime(-2);
        $first = $this->managerOne->getTime();
        $this->managerOne->modifyTime(10);
        $second = $this->managerOne->getTime();

        $this->assertEquals(498, $first);
        $this->assertEquals(508, $second);
    }
}