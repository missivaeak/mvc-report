<?php

namespace App\Roadlike;

use App\Roadlike\Road;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for Road
 */
final class RoadTest extends TestCase
{
    private Road $roadNone;
    private Road $roadOne;
    private Road $roadTwo;
    private Obstacle $obstacleOne;
    private Obstacle $obstacleTwo;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->obstacleOne = $this->createStub("App\Roadlike\Obstacle");
        $this->obstacleTwo = $this->createStub("App\Roadlike\Obstacle");
        $this->roadNone = new Road(100);
        $this->roadOne = new Road(110, [$this->obstacleOne]);
        $this->roadTwo = new Road(150, [$this->obstacleOne, $this->obstacleTwo]);
    }

    /**
     * Test instansiation
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Roadlike\Road", $this->roadNone);
        $this->assertInstanceOf("App\Roadlike\Road", $this->roadOne);
        $this->assertInstanceOf("App\Roadlike\Road", $this->roadTwo);
    }

    /**
     * Test get length
     */
    public function testGetLength(): void
    {
        $length = $this->roadOne->getLength();
        $this->assertEquals(110, $length);
    }

    /**
     * Test get obstacles
     */
    public function testGetObstacles(): void
    {
        $obstacles = $this->roadTwo->getObstacles();
        $this->assertEquals([
            $this->obstacleOne,
            $this->obstacleTwo
        ], $obstacles);
    }

    /**
     * Test add obstacle
     */
    public function testAddObstacle(): void
    {
        $this->roadNone->addObstacle($this->obstacleOne);
        $obstacles = $this->roadNone->getObstacles();
        $this->assertEquals([$this->obstacleOne], $obstacles);
    }

    /**
     * Test add length
     */
    public function testAddLength(): void
    {
        $this->roadTwo->addLength(22);
        $length = $this->roadTwo->getLength();
        $this->assertEquals(172, $length);
    }
}
