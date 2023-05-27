<?php

namespace App\Roadlike;

use App\Roadlike\Challenger;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for Challenger
 */
final class ChallengerTest extends TestCase
{
    private Challenger $challenger;
    private Challenger $challengerNoStats;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->challenger = new Challenger("Jens", [
            "intelligence" => 22,
            "strength" => 33,
            "dexterity" => 44,
            "luck" => 55,
            "speed" => 66,
            "constitution" => 11
        ]);
        $this->challengerNoStats = new Challenger("Jimmy", []);
    }

    /**
     * Test instansiation
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Roadlike\Challenger", $this->challenger);
        $this->assertInstanceOf("App\Roadlike\Challenger", $this->challengerNoStats);
    }

    /**
     * Test getStats
     */
    public function testGetStats(): void
    {
        $stats = $this->challenger->getStats();

        $this->assertEquals(22, $stats["intelligence"]);
        $this->assertEquals(33, $stats["strength"]);
        $this->assertEquals(44, $stats["dexterity"]);
        $this->assertEquals(55, $stats["luck"]);
        $this->assertEquals(66, $stats["speed"]);
        $this->assertEquals(11, $stats["constitution"]);
    }

    /**
     * Test getStats for challenger with no stats
     */
    public function testGetNoStats(): void
    {
        $stats = $this->challengerNoStats->getStats();

        $this->assertEquals(0, $stats["intelligence"]);
        $this->assertEquals(0, $stats["strength"]);
        $this->assertEquals(0, $stats["dexterity"]);
        $this->assertEquals(0, $stats["luck"]);
        $this->assertEquals(0, $stats["speed"]);
        $this->assertEquals(0, $stats["constitution"]);
    }
    /**
     * Test modify stats
     */
    public function testModifyStat(): void
    {
        $this->challenger->modifyStat("intelligence", 2);
        $this->challenger->modifyStat("strength", 2);
        $this->challenger->modifyStat("dexterity", 2);
        $this->challenger->modifyStat("luck", 2);
        $this->challenger->modifyStat("speed", 2);
        $this->challenger->modifyStat("constitution", 2);
        $stats = $this->challenger->getStats();
        $this->assertEquals(24, $stats["intelligence"]);
        $this->assertEquals(35, $stats["strength"]);
        $this->assertEquals(46, $stats["dexterity"]);
        $this->assertEquals(57, $stats["luck"]);
        $this->assertEquals(68, $stats["speed"]);
        $this->assertEquals(13, $stats["constitution"]);
    }

    /**
     * Test health for challenger with stats and without stats
     */
    public function testGetHealth(): void
    {
        $this->assertEquals(311, $this->challenger->getHealth());
        $this->assertEquals(300, $this->challengerNoStats->getHealth());
    }

    /**
     * Test get max health for challenger with stats and without stats
     */
    public function testGetMaxHealth(): void
    {
        $this->assertEquals(311, $this->challenger->getMaxHealth());
        $this->assertEquals(300, $this->challengerNoStats->getMaxHealth());
    }

    /**
     * Test modify health for challenger with and without stats
     */
    public function testModifyHealth(): void
    {
        $this->challenger->modifyHealth(-2);
        $this->assertEquals(309, $this->challenger->getHealth());
        $this->challenger->modifyHealth(1111);
        $this->assertEquals(311, $this->challenger->getHealth());
    }

    /**
     * Test stamina for challenger with stats and without stats
     */
    public function testGetStamina(): void
    {
        $this->assertEquals(311, $this->challenger->getStamina());
        $this->assertEquals(300, $this->challengerNoStats->getStamina());
    }

    /**
     * Test stamina for challenger with stats and without stats
     */
    public function testGetMaxStamina(): void
    {
        $this->assertEquals(311, $this->challenger->getMaxStamina());
        $this->assertEquals(300, $this->challengerNoStats->getMaxStamina());
    }

    /**
     * Test modify stamina for challenger with and without stats
     */
    public function testModifyStamina(): void
    {
        $this->challenger->modifyStamina(-2);
        $this->assertEquals(309, $this->challenger->getStamina());
        $this->challenger->modifyStamina(20);
        $this->assertEquals(311, $this->challenger->getStamina());
    }

    /**
     * Test get name
     */
    public function testGetName(): void
    {
        $this->assertEquals("Jens", $this->challenger->getName());
    }
}