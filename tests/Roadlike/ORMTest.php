<?php

namespace App\Roadlike;

use App\Roadlike\ORM;
use App\Entity\Obstacle as ObstacleEntity;

use Doctrine\ORM\EntityManagerInterface;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for ORM
 */
final class ORMTest extends TestCase
{
    private EntityManagerInterface $em;
    private ORM $orm;
    private array $obstacleData;
    private array $obstacleDataCC;
    private ObstacleEntity $obstacle;

    /**
     * setup
     */
    public function setUp(): void
    {
        $this->em = $this->createStub("Doctrine\ORM\EntityManagerInterface");
        $this->orm = new Orm($this->em);
        $this->obstacleData = [
            [
                'id' => 1,
                'name' => "hål",
                'description' => "farligt",
                'difficulty_int' => 10,
                'difficulty_str' => 0,
                'difficulty_dex' => null,
                'cost_reward_time' => 1,
                'cost_reward_health' => 0,
                'cost_reward_stamina' => -4,
                'cost_reward_int' => 0,
                'cost_reward_str' => 1,
                'cost_reward_dex' => -1,
                'cost_reward_lck' => 0,
                'cost_reward_spd' => 0,
                'cost_reward_con' => 0
            ]
        ];
        $this->obstacle = $this->createStub("App\Entity\Obstacle");
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getId')->willReturn(1);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getName')->willReturn('hål');
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getDescription')->willReturn('farligt');
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getDifficultyInt')->willReturn(10);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getDifficultyStr')->willReturn(0);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getDifficultyDex')->willReturn(null);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardTime')->willReturn(1);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardHealth')->willReturn(0);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardStamina')->willReturn(-4);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardInt')->willReturn(0);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardStr')->willReturn(1);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardDex')->willReturn(-1);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardLck')->willReturn(0);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardSpd')->willReturn(0);
        $this->obstacle-> /** @scrutinizer ignore-call */
            method('getCostRewardCon')->willReturn(0);
    }

    /**
     * Tests instansiate
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Roadlike\ORM", $this->orm);
    }

    /**
     * Test get all templates
     */
    public function testGetAllTemplates(): void
    {
        $repo = $this->createMock("App\Repository\TemplateRepository");
        $this->em-> /** @scrutinizer ignore-call */
            method('getRepository')->
            willReturn($repo);
        $template = $this->createStub("App\Entity\Template");
        $template-> /** @scrutinizer ignore-call */
            method('getName')->
            willReturn('test');
        $template-> /** @scrutinizer ignore-call */
            method('getId')->
            willReturn(1);
        $repo-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('findAll')->
            willReturn([
                $template
            ]);
        $templates = $this->orm->getAllTemplates();

        $this->assertEquals([["id"=> 1, "name" => "test"]], $templates);
    }

    /**
     * Test add template
     */
    public function testAddTemplate(): void
    {
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('persist')->
            with($this->isInstanceOf("App\Entity\Template"));
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('flush');

        $result = $this->orm->addTemplate("test");
        $this->assertEquals(["status" => "success", "template_added" => "test"], $result);
    }

    /**
     * Test delete template
     */
    public function testDelTemplate(): void
    {
        $repo = $this->createMock("App\Repository\TemplateRepository");
        $this->em-> /** @scrutinizer ignore-call */
            method('getRepository')->
            willReturn($repo);
        $template = $this->createStub("App\Entity\Template");
        $template-> /** @scrutinizer ignore-call */
            method('getName')->
            willReturn('test');
        $repo-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('find')->
            with(4)->
            willReturn($template);
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('remove')->
            with($template);
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('flush');

        $result = $this->orm->delTemplate(4);
        $this->assertEquals(["status" => "success", "template_deleted" => "test"], $result);
    }

    /**
     * Test delete template not found
     */
    public function testDelTemplateNotFound(): void
    {
        $repo = $this->createMock("App\Repository\TemplateRepository");
        $this->em-> /** @scrutinizer ignore-call */
            method('getRepository')->
            willReturn($repo);
        $repo-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('find')->
            with(4);

        $result = $this->orm->delTemplate(4);
        $this->assertEquals(["status" => "failed", "template_deleted" => null], $result);
    }

    /**
     * Test get all obstacles
     */
    public function testGetAllObstacles(): void
    {
        $repo = $this->createMock("App\Repository\ObstacleRepository");
        $this->em-> /** @scrutinizer ignore-call */
            method('getRepository')->
            willReturn($repo);
        $repo-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('findAll')->
            willReturn([$this->obstacle]);
        $obstacles = $this->orm->getAllObstacles();

        $this->assertEquals($this->obstacleData, $obstacles);
    }

    /**
     * Test add obstacle
     */
    public function testAddObstacle(): void
    {
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('persist')->
            with($this->isInstanceOf("App\Entity\Obstacle"));
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('flush');

        $result = $this->orm->addObstacle([
            'id' => 1,
            'name' => "hål",
            'description' => "farligt",
            'difficultyInt' => 10,
            'difficultyStr' => 0,
            'difficultyDex' => null,
            'costRewardTime' => 1,
            'costRewardHealth' => 0,
            'costRewardStamina' => -4,
            'costRewardInt' => 0,
            'costRewardStr' => 1,
            'costRewardDex' => -1,
            'costRewardLck' => 0,
            'costRewardSpd' => 0,
            'costRewardCon' => 0
        ]);
        $this->assertEquals(["status" => "success", "obstacle_added" => "hål"], $result);
    }

    /**
     * Test delete obstacle
     */
    public function testDelObstacle(): void
    {
        $repo = $this->createMock("App\Repository\ObstacleRepository");
        $this->em-> /** @scrutinizer ignore-call */
            method('getRepository')->
            willReturn($repo);
        $obstacle = $this->createStub("App\Entity\Obstacle");
        $obstacle-> /** @scrutinizer ignore-call */
            method('getName')->
            willReturn('test');
        $repo-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('find')->
            with(4)->
            willReturn($obstacle);
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('remove')->
            with($obstacle);
        $this->em-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('flush');

        $result = $this->orm->delObstacle(4);
        $this->assertEquals(["status" => "success", "obstacle_deleted" => "test"], $result);
    }

    /**
     * Test delete obstacle not found
     */
    public function testDelObstacleNotFound(): void
    {
        $repo = $this->createMock("App\Repository\ObstacleRepository");
        $this->em-> /** @scrutinizer ignore-call */
            method('getRepository')->
            willReturn($repo);
        $repo-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('find')->
            with(4);

        $result = $this->orm->delObstacle(4);
        $this->assertEquals(["status" => "failed", "obstacle_deleted" => null], $result);
    }
}