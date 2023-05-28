<?php

namespace App\Roadlike;

use App\Roadlike\Factory;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;
use TypeError;

/**
 * Testsuit for Factory
 */
final class FactoryTest extends TestCase
{
    /** @var array<array{id: int, name: string, description: string, difficulty_int: ?int, difficulty_str: ?int, difficulty_dex: ?int, cost_reward_time: int, cost_reward_health: int, cost_reward_stamina: int, cost_reward_int: int, cost_reward_str: int, cost_reward_dex: int, cost_reward_lck: int, cost_reward_spd: int, cost_reward_con: int}> */
    private array $obstacleData;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->obstacleData = [
            [
                'id' => 1,
                'name' => "grop1",
                'description' => "farlig!",
                'difficulty_int' => 10,
                'difficulty_str' => 0,
                'difficulty_dex' => null,
                'cost_reward_time' => -2,
                'cost_reward_health' => 0,
                'cost_reward_stamina' => -1,
                'cost_reward_int' => 1,
                'cost_reward_str' => 0,
                'cost_reward_dex' => 0,
                'cost_reward_lck' => 0,
                'cost_reward_spd' => -1,
                'cost_reward_con' => 0
            ],
            [
                'id' => 2,
                'name' => "grop2",
                'description' => "farlig!",
                'difficulty_int' => 10,
                'difficulty_str' => 0,
                'difficulty_dex' => null,
                'cost_reward_time' => -2,
                'cost_reward_health' => 0,
                'cost_reward_stamina' => -1,
                'cost_reward_int' => 1,
                'cost_reward_str' => 0,
                'cost_reward_dex' => 0,
                'cost_reward_lck' => 0,
                'cost_reward_spd' => -1,
                'cost_reward_con' => 0
            ],
            [
                'id' => 3,
                'name' => "grop3",
                'description' => "farlig!",
                'difficulty_int' => 10,
                'difficulty_str' => 0,
                'difficulty_dex' => null,
                'cost_reward_time' => -2,
                'cost_reward_health' => 0,
                'cost_reward_stamina' => -1,
                'cost_reward_int' => 1,
                'cost_reward_str' => 0,
                'cost_reward_dex' => 0,
                'cost_reward_lck' => 0,
                'cost_reward_spd' => -1,
                'cost_reward_con' => 0
            ],
            [
                'id' => 4,
                'name' => "grop4",
                'description' => "farlig!",
                'difficulty_int' => 10,
                'difficulty_str' => 0,
                'difficulty_dex' => null,
                'cost_reward_time' => -2,
                'cost_reward_health' => 0,
                'cost_reward_stamina' => -1,
                'cost_reward_int' => 1,
                'cost_reward_str' => 0,
                'cost_reward_dex' => 0,
                'cost_reward_lck' => 0,
                'cost_reward_spd' => -1,
                'cost_reward_con' => 0
            ]
        ];
    }

    /**
     * Tests building a manager
     */
    public function testBuildManager(): void
    {
        $factory = new Factory();
        $challenger = $this->createStub("App\Roadlike\Challenger");
        $manager = $factory->buildManager($challenger);

        $this->assertEquals($challenger, $manager->getChallenger());
        $this->assertInstanceOf("App\Roadlike\Road", $manager->getJourney());
        $this->assertNull($manager->getCrossroads());
    }

    /**
     * Tests building a draft with an improper array
     */
    public function testBuildDraft(): void
    {
        $factory = new Factory();
        $templates = [
            ["name" => "ettan"],
            ["name" => "tvåan"],
            ["name" => "trean"],
            ["name" => "fyran"],
            ["name" => "femman"],
            ["name" => "sexan"]
        ];
        $draft = $factory->buildDraft($templates, 3);

        $this->assertContainsOnly("App\Roadlike\Challenger", $draft);
        $this->assertCount(3, $draft);
    }

    /**
     * Tests building a draft with a bad array
     */
    public function testBuildDraftBadArray(): void
    {
        $factory = new Factory();
        $templates = [
            ["nameoo" => "ettan"],
            ["name" => null],
            ["name" => "trean"]
        ];
        $this->expectException('TypeError');
        $factory->buildDraft($templates, 3);
    }

    /**
     * Tests building a draft with an empty array
     */
    public function testBuildDraftEmptyArray(): void
    {
        $factory = new Factory();
        $templates = [];
        $draft = $factory->buildDraft($templates, 3);

        $this->assertEquals([], $draft);
    }

    /**
     * Test building obstacles
     */
    public function testBuildObstacles(): void
    {
        $factory = new Factory();
        $obstacles = $factory->buildObstacles($this->obstacleData, 2);

        $this->assertContainsOnly("App\Roadlike\Obstacle", $obstacles);
        $this->assertCount(2, $obstacles);
    }

    /**
     * Test building crossroads
     */
    public function testBuildCrossroads(): void
    {
        $factory = new Factory();
        $crossroads = $factory->buildCrossroads($this->obstacleData, 2, 3);

        $this->assertInstanceOf("App\Roadlike\Crossroads", $crossroads);
        $this->assertContainsOnly("App\Roadlike\Road", $crossroads->getPaths());
    }
}
