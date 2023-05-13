<?php

namespace App\Game;

use App\Game\Round;

use PHPUnit\Framework\TestCase;

/**
 * Testsvit för Round
 */
final class RoundTest extends TestCase
{
    private Player $player;
    private Player $opponent;
    private Round $round;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $this->player = $this->createStub(Player::class);
        $this->opponent = $this->createStub(Player::class);
        $this->round = new Round($this->player, $this->opponent);
    }

    /**
     * Testar instansiering
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Game\Round", $this->round);
    }

    /**
     * Testar att randomiseDealer ger båda
     * spelarnas returneras över 100 repetetioner
     */
    public function testRandomiseDealer(): void
    {
        $playerDealers = 0;
        $opponentDealers = 0;

        for ($i = 0; $i < 100; $i++) {
            $result = $this->round->randomiseDealer();

            if ($result === $this->player) {
                $playerDealers += 1;
            }

            if ($result === $this->opponent) {
                $opponentDealers += 1;
            }
        }

        $this->assertGreaterThan(0, $playerDealers);
        $this->assertGreaterThan(0, $opponentDealers);
        $this->assertSame(100, $playerDealers + $opponentDealers);
    }

    /**
     * Testar att setDealer och getDealer fungerar
     */
    public function testGetSetDealer(): void
    {
        $this->round->setDealer($this->player);
        $gottenDealer = $this->round->getDealer();
        $this->assertSame($this->player, $gottenDealer);
    }

    /**
     * Testar att getActivePlayer funkar efter randomiseDealer
     */
    public function testGetActivePlayerAfterRandomise(): void
    {
        $this->round->randomiseDealer();
        $gottenActivePlayer = $this->round->getActivePlayer();
        $this->assertInstanceOf("App\Game\Player", $gottenActivePlayer);
    }

    /**
     * Testar att getActivePlayer funkar efter
     * setDealer och autoSetActiveDealer
     */
    public function testGetActivePlayerAfterSet(): void
    {
        $this->round->setDealer($this->player);
        $this->round->autoSetActivePlayer();
        $gottenActivePlayer = $this->round->getActivePlayer();
        $this->assertSame($this->opponent, $gottenActivePlayer);
    }

    /**
     * Testar att getNextDealer funkar efter randomiseDealer
     */
    public function testGetNextDealer(): void
    {
        $this->round->randomiseDealer();
        $firstDealer = $this->round->getDealer();
        $secondDealer = $this->round->getNextDealer();
        $this->assertInstanceOf("App\Game\Player", $firstDealer);
        $this->assertInstanceOf("App\Game\Player", $secondDealer);
        $this->assertNotSame($firstDealer, $secondDealer);
    }

    /**
     * Testar att nextTurn byter activePlayer
     */
    public function testNextTurn(): void
    {
        $this->round->setDealer($this->player);
        $this->round->autoSetActivePlayer();
        $this->round->nextTurn();
        $gottenPlayer = $this->round->getActivePlayer();
        $this->assertSame($this->player, $gottenPlayer);
    }

    /**
     * Testar att getStep och setStup funkar
     */
    public function testGetSetStep(): void
    {
        $this->assertSame(4, $this->round->getStep());
        $this->round->setStep(2);
        $this->assertSame(2, $this->round->getStep());
    }

    /**
     * Testar att getStep och setStup funkar
     */
    public function testNextStep(): void
    {
        $this->round->nextStep();
        $this->assertSame(5, $this->round->getStep());
    }

    /**
     * Testar att deal kallar på rätt funktioner i
     * player, opponent och kortleken
     */
    public function testDeal(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);

        $deck = $this->createStub(StandardPlayingCardsDeck::class);
        $deck-> /** @scrutinizer ignore-call */
            expects($this->exactly(2))->
            method('draw')->
            willReturn($card);

        $playerHand = $this->createStub(GinRummyHand::class);
        $playerHand-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('add')->
            with($card);

        $player = $this->player;
        $player-> /** @scrutinizer ignore-call */
            method('getHand')->
            willReturn($playerHand);

        $opponentHand = $this->createStub(GinRummyHand::class);
        $opponentHand-> /** @scrutinizer ignore-call */
            expects($this->once())->
            method('add')->
            with($card);

        $opponent = $this->opponent;
        $opponent-> /** @scrutinizer ignore-call */
            method('getHand')->
            willReturn($opponentHand);

        $this->round->deal($deck);
    }
}
