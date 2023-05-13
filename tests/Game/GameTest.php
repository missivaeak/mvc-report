<?php

namespace App\Game;

use App\Game\Game;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för Game
 */
final class GameTest extends TestCase
{
    private Player $player;
    private GinRummyHand $playerHand;
    private Player $opponent;
    private GinRummyHand $opponentHand;
    private StandardPlayingCardsDeck $deck;
    private Discard $discard;
    private Game $game;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $player = $this->createStub(Player::class);
        $playerHand = $this->createStub(GinRummyHand::class);
        $player->method('getHand')->willReturn($playerHand);
        $this->player = $player;
        $this->playerHand = $playerHand;

        $opponent = $this->createStub(Player::class);
        $opponentHand = $this->createStub(GinRummyHand::class);
        $opponent->method('getHand')->willReturn($opponentHand);
        $this->opponent = $opponent;
        $this->opponentHand = $opponentHand;

        $this->deck = $this->createStub(StandardPlayingCardsDeck::class);
        $this->discard = $this->createStub(Discard::class);
        $this->game = new Game($this->player, $this->opponent, $this->deck, $this->discard);
    }

    /**
     * Testar instansiering
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Game\Game", $this->game);
    }

    /**
     * Testar att getPlayer ger spelaren
     */
    public function testGetPlayer(): void
    {
        $player = $this->game->getPlayer();

        $this->assertSame($player, $this->player);
    }

    /**
     * Testar att getOpponent ger motståndaren
     */
    public function testGetOpponent(): void
    {
        $opponent = $this->game->getOpponent();

        $this->assertSame($opponent, $this->opponent);
    }

    /**
     * Testar att getKnockThreshold ger ett heltal
     */
    public function testGetKnockThreshold(): void
    {
        $this->assertIsInt($this->game->getKnockThreshold());
    }

    /**
     * Testar att getGinBonus ger ett heltal
     */
    public function testGetGinBonus(): void
    {
        $this->assertIsInt($this->game->getGinBonus());
    }

    /**
     * Testar att getUndercutBonus ger ett heltal
     */
    public function testGetUndercutBonus(): void
    {
        $this->assertIsInt($this->game->getUndercutBonus());
    }

    /**
     * Testar att getKnockBonus ger ett heltal
     */
    public function testGetKnockBonus(): void
    {
        $this->assertIsInt($this->game->getKnockBonus());
    }

    /**
     * Testar att getHandSize ger ett heltal
     */
    public function testGetHandSize(): void
    {
        $this->assertIsInt($this->game->getHandSize());
    }

    /**
     * Testar att getWinThreshold ger ett heltal
     */
    public function testGetWinThreshold(): void
    {
        $this->assertIsInt($this->game->getWinThreshold());
    }

    /**
     * Testar att setRound() och getRound() fungerar
     */
    public function testGetSetRound(): void
    {
        $round = $this->createStub(Round::class);
        $this->game->setRound($round);

        $this->assertSame($round, $this->game->getRound());
    }

    /**
     * Testar att deal() kallar på draw
     * rätt antal gånger och med rätt parameter
     */
    public function testDeal(): void
    {
        $handSize = $this->game->getHandSize();
        $round = $this->createMock(Round::class);
        $round-> /** @scrutinizer ignore-call */
            expects($this->exactly($handSize))->
            method('deal')->
            with($this->identicalTo($this->deck));

        $this->game->setRound($round);

        $this->game->deal();
    }

    /**
     * Testar att deckToDiscard
     */
    public function testDeckToDiscard(): void
    {
        $card = $this->createMock(StandardPlayingCard::class);
        $deck = $this->deck;
        $discard = $this->discard;

        $deck->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('draw')->
            willReturn($card);

        $card->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('reveal');

        $discard->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('add')->
            with($this->identicalTo($card));

        $success = $this->game->deckToDiscard();
        $this->assertTrue($success);
    }

    /**
     * Testar deckToDiscard på en tom lek
     */
    public function testDeckToDiscardEmpty(): void
    {
        $deck = $this->deck;
        $discard = $this->discard;

        $deck->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('draw')->
            willReturn(null);

        $discard->/** @scrutinizer ignore-call */
            expects($this->never())->
            method('add');

        $success = $this->game->deckToDiscard();
        $this->assertFalse($success);
    }

    /**
     * Testar att returnCards kallar på draw från
     * båda spelarnas händer och discard
     */
    public function testReturnCards(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);

        $playerHand = $this->playerHand;
        $playerHand->/** @scrutinizer ignore-call */
            method('getCardsRemaining')->
            willReturn(1);
        $playerHand->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('draw')->
            willReturn($card);

        $opponentHand = $this->opponentHand;
        $opponentHand->/** @scrutinizer ignore-call */
            method('getCardsRemaining')->
            willReturn(1);
        $opponentHand->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('draw')->
            willReturn($card);

        $discard = $this->discard;
        $discard->/** @scrutinizer ignore-call */
            method('getCardsRemaining')->
            willReturn(1);
        $discard->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('draw')->
            willReturn($card);

        $deck = $this->deck;
        $deck->/** @scrutinizer ignore-call */
            expects($this->exactly(3))->
            method('add')->
            with($this->identicalTo($card));

        $this->game->returnCards();
    }

    public function testGetPlayerHand(): void
    {
        $gottenPlayerHand = $this->game->getPlayerHand();

        $this->assertSame($this->playerHand, $gottenPlayerHand);
    }

    public function testGetOpponentHand(): void
    {
        $gottenOpponentHand = $this->game->getOpponentHand();

        $this->assertSame($this->opponentHand, $gottenOpponentHand);
    }

    public function testGetDeck(): void
    {
        $gottenDeck = $this->game->getDeck();

        $this->assertSame($this->deck, $gottenDeck);
    }

    public function testGetDiscard(): void
    {
        $gottenDiscard = $this->game->getDiscard();

        $this->assertSame($this->discard, $gottenDiscard);
    }

    public function testScoreKnockingWins(): void
    {
        $knockBonus = $this->game->getKnockBonus();
        $knocking = $this->player;
        $knocking->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('addScore')->
            with(
                $this->equalTo(20 + $knockBonus)
            );
        $notKnocking = $this->opponent;

        $amount = $this->game->score($knocking, $notKnocking, 20);

        $this->assertSame(20, $amount);
    }

    public function testScoreNotKnockingWins(): void
    {
        $undercutBonus = $this->game->getUndercutBonus();
        $knocking = $this->player;

        $notKnocking = $this->opponent;
        $notKnocking->/** @scrutinizer ignore-call */
            expects($this->once())->
            method('addScore')->
            with(
                $this->equalTo(20 + $undercutBonus)
            );

        $amount = $this->game->score($knocking, $notKnocking, -20);

        $this->assertSame(-45, $amount);
    }

    public function testScoreEqual(): void
    {
        $knocking = $this->player;
        $notKnocking = $this->opponent;

        $amount = $this->game->score($knocking, $notKnocking, 0);

        $this->assertSame(0, $amount);
    }
}
