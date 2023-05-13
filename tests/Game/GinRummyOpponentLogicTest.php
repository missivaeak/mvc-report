<?php

namespace App\Game;

use App\Game\GinRummyOpponentLogic;
use App\Game\Game;
use App\Game\GinRummyScoring;
use App\Game\Player;
use App\Game\Discard;
use App\Game\GinRummyHand;
use App\Game\StandardPlayingCardsDeck;
use App\Game\StandardPlayingCard;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för GinRummyOpponentLogic
 */
final class GinRummyOpponentLogicTest extends TestCase
{
    private GinRummyOpponentLogic $logic;
    private Game $game;
    private GinRummyScoring $scoring;
    private GinRummyHand $opponentHand;
    private Discard $discard;
    private StandardPlayingCardsDeck $deck;
    private StandardPlayingCard $card;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);

        $opponentHand = $this->createStub(GinRummyHand::class);
        $opponentHand->method('draw')->willReturn($card);

        $opponent = $this->createStub(Player::class);
        $opponent->method('getHand')->willReturn($opponentHand);

        $discard = $this->createStub(Discard::class);

        $deck = $this->createStub(StandardPlayingCardsDeck::class);

        $game = $this->createStub(Game::class);
        $game->method('getOpponent')->willReturn($opponent);
        $game->method('getDiscard')->willReturn($discard);
        $game->method('getDeck')->willReturn($deck);
        $game->method('getKnockThreshold')->willReturn(10);

        $this->game = $game;
        $this->scoring = $this->createStub(GinRummyScoring::class);
        $this->logic = new GinRummyOpponentLogic($this->game, $this->scoring);
        $this->opponentHand = $opponentHand;
        $this->discard = $discard;
        $this->deck = $deck;
        $this->card = $card;
    }

    /**
     * Testar att instansieringen funkar
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Game\GinRummyOpponentLogic", $this->logic);
    }

    /**
     * Testar att pickDiscard returnerar ett kort
     */
    public function testPickDiscard(): void
    {
        $discard = $this->discard;
        $discard-> /** @scrutinizer ignore-call */
            method('draw')->
            willReturn($this->card);
        $pickedCard = $this->logic->pickDiscard();

        $this->assertSame($this->card, $pickedCard);
    }

    /**
     * Testar att pickDiscard returnerar null i en tom discard
     */
    public function testPickDiscardEmptyDiscard(): void
    {
        $discard = $this->discard;
        $discard-> /** @scrutinizer ignore-call */
            method('draw')->
            willReturn(null);
        $pickedCard = $this->logic->pickDiscard();

        $this->assertSame(null, $pickedCard);
    }

    /**
     * Testar att pickDeck returnerar ett kort
     */
    public function testPickDeck(): void
    {
        $deck = $this->deck;
        $deck-> /** @scrutinizer ignore-call */
            method('draw')->
            willReturn($this->card);
        $pickedCard = $this->logic->pickDeck();

        $this->assertSame($this->card, $pickedCard);
    }

    /**
     * Testar att pickDeck returnerar null i en tom deck
     */
    public function testPickDeckEmptyDeck(): void
    {
        $deck = $this->deck;
        $deck-> /** @scrutinizer ignore-call */
            method('draw')->
            willReturn(null);
        $pickedCard = $this->logic->pickDeck();

        $this->assertSame(null, $pickedCard);
    }

    /**
     * Testar att discard returnerar
     * ett kort om handen inte är tom
     */
    public function testDiscard(): void
    {
        $card1 = clone $this->card;
        $card1-> /** @scrutinizer ignore-call */
            method('getValue')->
            willReturn(2);

        $card2 = clone $this->card;
        $card2-> /** @scrutinizer ignore-call */
            method('getValue')->
            willReturn(4);

        $hand = $this->opponentHand;
        $hand-> /** @scrutinizer ignore-call */
            method('getUnmatched')->
            willReturn([$card2, $card1]);
        $success = false;
        for ($i = 0; $i < 100; $i++) {
            $discardedCard = $this->logic->discard();
            if ($card2 === $discardedCard) {
                $success = true;
            }
        }

        $this->assertTrue($success);
    }

    /**
     * Testar att discard returnerar
     * ett kort om handen är tom
     */
    public function testDiscardEmptyHand(): void
    {
        $hand = $this->opponentHand;
        $hand-> /** @scrutinizer ignore-call */
            method('getUnmatched')->
            willReturn([]);
        $discardedCard = $this->logic->discard();

        $this->assertNull($discardedCard);
    }

    /**
     * Testar om drawOrPass ger CardInterface eller null
     */
    public function testDrawOrPass(): void
    {
        $this->discard-> /** @scrutinizer ignore-call */
            method('draw')->
            willReturn($this->card);
        $cardOnceFlag = false;
        $nullOnceFlag = false;

        for ($i = 0; $i < 100; $i++) {
            $result = $this->logic->drawOrPass();
            if ($result instanceof StandardPlayingCard) {
                $cardOnceFlag = true;
            }

            if ($result === null) {
                $nullOnceFlag = true;
            }
        }

        $this->assertTrue($cardOnceFlag && $nullOnceFlag);
    }

    /**
     * Testar om drawDeckOrDrawDiscard ger CardInterface eller null
     */
    public function testDrawDeckOrDrawDiscard(): void
    {
        $this->deck-> /** @scrutinizer ignore-call */
            method('draw')->
            willReturn($this->card);
        $this->discard-> /** @scrutinizer ignore-call */
            method('draw')->
            willReturn($this->card);
        $drawDeckOnceFlag= false;
        $drawDiscardOnceFlag = false;

        for ($i = 0; $i < 100; $i++) {
            $result = $this->logic->drawDeckOrDrawDiscard();
            if ($result instanceof StandardPlayingCard) {
                $drawDiscardOnceFlag = true;
            }

            if ($result === null) {
                $drawDeckOnceFlag = true;
            }
        }

        $this->assertTrue($drawDeckOnceFlag && $drawDiscardOnceFlag);
    }

    /**
     * Testar om knockOrPass returnerar true när spelaren kan knacka
     */
    public function testKnockOrPassCanKnock(): void
    {
        $scoring = $this->scoring;
        $scoring-> /** @scrutinizer ignore-call */
            method('handScore')->
            willReturn(6);

        $this->assertTrue($this->logic->knockOrPass());
    }

    /**
     * Testar om knockOrPass returnerar true när spelaren inte kan knacka
     */
    public function testKnockOrPassCannotKnock(): void
    {
        $scoring = $this->scoring;
        $scoring-> /** @scrutinizer ignore-call */
            method('handScore')->
            willReturn(11);

        $this->assertFalse($this->logic->knockOrPass());
    }

    /**
     * Testar om addToPlayersMeld returnerar true
     * om minst ett kort passar i en meld
     */
    public function testAddToPlayersMeld(): void
    {
        $hand = $this->opponentHand;
        $hand-> /** @scrutinizer ignore-call */
            method('getUnmatched')->
            willReturn([
                $this->card,
                clone $this->card,
                clone $this->card
            ]);

        $scoring = $this->scoring;
        $scoring-> /** @scrutinizer ignore-call */
            method('addToOthersMeld')->
            will($this->onConsecutiveCalls(
                true,
                false,
                false,
                false,
                false,
                false
            ));

        $playerHand = $this->createStub(GinRummyHand::class);

        $success = $this->logic->addToPlayersMeld($playerHand);
        $this->assertTrue($success);
    }
}
