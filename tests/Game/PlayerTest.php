<?php

namespace App\Game;

use App\Game\Player;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för Player
 */
final class PlayerTest extends TestCase
{
    private Player $player;
    private GinRummyHand $hand;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $this->hand = $this->createStub(GinRummyHand::class);
        $this->player = new Player($this->hand);
    }

    /**
     * Testar att instansiering funkar
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Game\Player", $this->player);
    }

    /**
     * Testar att getHand returnerar handen
     */
    public function testGetHand(): void
    {
        $gottenHand = $this->player->getHand();
        $this->assertSame($this->hand, $gottenHand);
    }

    /**
     * Testar att getScore ger 0 på ett nytt objekt
     */
    public function testGetScore(): void
    {
        $gottenScore = $this->player->getScore();
        $this->assertSame(0, $gottenScore);
    }

    /**
     * Testar att getScore ger 10 på ett objekt
     * efter att addScore lagt till 10 poäng
     */
    public function testAddScore(): void
    {
        $this->player->addScore(10);
        $gottenScore = $this->player->getScore();
        $this->assertSame(10, $gottenScore);
    }
}
