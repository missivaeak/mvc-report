<?php

namespace App\Roadlike;

use App\Roadlike\Obstacle;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for Obstacle
 */
final class ObstacleTest extends TestCase
{
    private Obstacle $obstacleOne;
    private Obstacle $obstacleTwo;
    private Challenger $challengerLucky;
    private Challenger $challengerUnlucky;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->challengerLucky = $this->createStub("App\Roadlike\Challenger");
        $this->challengerLucky-> /** @scrutinizer ignore-call */
            method('getStats')->
            willReturn([
                "intelligence" => 10,
                "strength" => 1,
                "dexterity" => 44,
                "luck" => 101,
                "speed" => 66,
                "constitution" => 11
            ]);

        $this->challengerUnlucky = $this->createStub("App\Roadlike\Challenger");
        $this->challengerUnlucky-> /** @scrutinizer ignore-call */
            method('getStats')->
            willReturn([
                "intelligence" => 10,
                "strength" => 1,
                "dexterity" => 44,
                "luck" => 0,
                "speed" => 66,
                "constitution" => 11
            ]);

        $this->obstacleOne = new Obstacle("namn", "beskrivning", [
            "intelligence" => 10,
            "strength" => 0
        ], [
            "time" => 22,
            "health" => -4,
            "stamina" => -13,
            "intelligence" => -5,
            "strength" => 2,
            "dexterity" => 0,
            "luck" => 0,
            "speed" => 0,
            "constitution" => 0
        ]);

        $this->obstacleTwo = new Obstacle("namn", "beskrivning", [], [
            "time" => 22,
            "health" => -4,
            "stamina" => -13,
            "intelligence" => -5,
            "strength" => 2,
            "dexterity" => 0,
            "luck" => 0,
            "speed" => 0,
            "constitution" => 0
        ]);
    }

    /**
     * Test instansiation
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Roadlike\Obstacle", $this->obstacleOne);
    }

    /**
     * Tests name getter
     */
    public function testGetName(): void
    {
        $this->assertEquals("namn", $this->obstacleOne->getName());
    }

    /**
     * Tests description getter
     */
    public function testGetDescription(): void
    {
        $this->assertEquals("beskrivning", $this->obstacleOne->getDescription());
    }

    /**
     * Test an attempt at the obstacle with an unlucky challenger
     */
    public function testAttemptUnlucky(): void
    {
        $resultOne = $this->obstacleOne->attempt($this->challengerUnlucky);
        $resultTwo = $this->obstacleTwo->attempt($this->challengerUnlucky);

        $this->assertEquals([
            "time" => 5,
            "health" => -1,
            "stamina" => -2,
            "intelligence" => -7,
            "strength" => 1,
            "dexterity" => 0,
            "luck" => 0,
            "speed" => 0,
            "constitution" => 0
        ], $resultOne["deltas"]);
        $this->assertEquals([
            "time" => 3,
            "health" => -1,
            "stamina" => -3,
            "intelligence" => -5,
            "strength" => 2,
            "dexterity" => 0,
            "luck" => 0,
            "speed" => 0,
            "constitution" => 0
        ], $resultTwo["deltas"]);
        $this->assertFalse($resultOne["lucky"]);
        $this->assertFalse($resultTwo["lucky"]);
    }

    /**
     * Test an attempt at the obstacle with a lucky challenger
     * that the result deltas match expected values and that lucky is
     * true if there were stat tests, and false if there were not
     */
    public function testAttemptLucky(): void
    {
        $resultOne = $this->obstacleOne->attempt($this->challengerLucky);
        $resultTwo = $this->obstacleTwo->attempt($this->challengerLucky);

        $this->assertEquals([
            "time" => 2,
            "health" => -1,
            "stamina" => -4,
            "intelligence" => -4,
            "strength" => 3,
            "dexterity" => 0,
            "luck" => 0,
            "speed" => 0,
            "constitution" => 0
        ], $resultOne["deltas"]);
        $this->assertEquals([
            "time" => 3,
            "health" => -1,
            "stamina" => -3,
            "intelligence" => -5,
            "strength" => 2,
            "dexterity" => 0,
            "luck" => 0,
            "speed" => 0,
            "constitution" => 0
        ], $resultTwo["deltas"]);
        $this->assertTrue($resultOne["lucky"]);
        $this->assertFalse($resultTwo["lucky"]);
    }
}
