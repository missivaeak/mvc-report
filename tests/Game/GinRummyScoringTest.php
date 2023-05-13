<?php

namespace App\Game;

use App\Game\GinRummyScoring;
use App\Game\StandardPlayingCard;
use App\Game\GinRummyHand;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för Scoring
 */
final class GinRummyScoringTest extends TestCase
{
    private GinRummyScoring $scoring;
    private GinRummyHand $hand;
    private array $cards;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $cards = [$this->createStub(StandardPlayingCard::class)];
        for ($i = 0; $i < 9; $i++) {
            $card = clone $cards[0];
            $cards[] = $card;
        }
        $cards[0]->method('getValue')->willReturn(3);
        $cards[0]->method('getSuit')->willReturn("clubs");

        $cards[1]->method('getValue')->willReturn(3);
        $cards[1]->method('getSuit')->willReturn("hearts");

        $cards[2]->method('getValue')->willReturn(3);
        $cards[2]->method('getSuit')->willReturn("diamonds");

        $cards[3]->method('getValue')->willReturn(4);
        $cards[3]->method('getSuit')->willReturn("diamonds");

        $cards[4]->method('getValue')->willReturn(5);
        $cards[4]->method('getSuit')->willReturn("diamonds");

        $cards[5]->method('getValue')->willReturn(6);
        $cards[5]->method('getSuit')->willReturn("diamonds");

        $cards[6]->method('getValue')->willReturn(6);
        $cards[6]->method('getSuit')->willReturn("hearts");

        $cards[7]->method('getValue')->willReturn(10);
        $cards[7]->method('getSuit')->willReturn("clubs");

        $cards[8]->method('getValue')->willReturn(13);
        $cards[8]->method('getSuit')->willReturn("diamonds");

        $cards[9]->method('getValue')->willReturn(11);
        $cards[9]->method('getSuit')->willReturn("clubs");

        $hand = $this->createStub(GinRummyHand::class);
        $hand->method('getCards')->willReturn($cards);

        $this->hand = $hand;
        $this->cards = $cards;
        $this->scoring = new GinRummyScoring();
    }

    /**
     * Testar att meld kallar på addMeld, addToMeld
     * samt att meld returnerar ett heltal
     */
    public function testMeld(): void
    {
        $hand = $this->hand;
        // $hand->method('getUnmatched')->willReturn($this->cards);
        $hand-> /** @scrutinizer ignore-call */
            expects($this->atLeastOnce())->
            method('getMelds');

        $score = $this->scoring->meld($hand);

        $this->assertIsInt($score);
    }

    public function testMeldSetsFirstNoMock(): void
    {
        $hand = new GinRummyHand();
        $hand->add(new StandardPlayingCard("clubs", 3));
        $hand->add(new StandardPlayingCard("hearts", 3));
        $hand->add(new StandardPlayingCard("diamonds", 3));
        $hand->add(new StandardPlayingCard("diamonds", 4));
        $hand->add(new StandardPlayingCard("diamonds", 5));
        $hand->add(new StandardPlayingCard("diamonds", 6));
        $hand->add(new StandardPlayingCard("hearts", 6));
        $hand->add(new StandardPlayingCard("clubs", 10));
        $hand->add(new StandardPlayingCard("diamonds", 13));
        $hand->add(new StandardPlayingCard("clubs", 11));

        $score = $this->scoring->meld($hand);
        $this->assertSame(36, $score);
    }

    public function testMeldRunsFirstNoMock(): void
    {
        $hand = new GinRummyHand();
        $hand->add(new StandardPlayingCard("clubs", 3));
        $hand->add(new StandardPlayingCard("hearts", 3));
        $hand->add(new StandardPlayingCard("diamonds", 3));
        $hand->add(new StandardPlayingCard("diamonds", 4));
        $hand->add(new StandardPlayingCard("diamonds", 5));
        $hand->add(new StandardPlayingCard("hearts", 4));
        $hand->add(new StandardPlayingCard("hearts", 5));
        $hand->add(new StandardPlayingCard("clubs", 10));
        $hand->add(new StandardPlayingCard("diamonds", 13));
        $hand->add(new StandardPlayingCard("clubs", 11));

        $score = $this->scoring->meld($hand);
        $this->assertSame(33, $score);
    }

    public function testAddToOthersMeldSetNoMock(): void
    {
        $otherHand = new GinRummyHand();
        $otherHand->add(new StandardPlayingCard("clubs", 3));
        $otherHand->add(new StandardPlayingCard("hearts", 3));
        $otherHand->add(new StandardPlayingCard("diamonds", 3));
        $otherHand->add(new StandardPlayingCard("diamonds", 4));
        $otherHand->add(new StandardPlayingCard("diamonds", 5));
        $otherHand->add(new StandardPlayingCard("diamonds", 6));
        $otherHand->add(new StandardPlayingCard("hearts", 6));
        $otherHand->add(new StandardPlayingCard("clubs", 10));
        $otherHand->add(new StandardPlayingCard("diamonds", 13));
        $otherHand->add(new StandardPlayingCard("clubs", 11));
        $meld = new Meld("set");
        $otherHand->addMeld($meld);
        $otherHand->addToMeld(0, 0);
        $otherHand->addToMeld(1, 0);
        $otherHand->addToMeld(2, 0);

        $thisHand = new GinRummyHand();
        $thisHand->add(new StandardPlayingCard("spades", 3));

        $this->scoring->addToOthersMeld("spades", 3, $thisHand, $otherHand);

        $this->assertSame(0, $thisHand->getCardsRemaining());
        $this->assertSame(11, $otherHand->getCardsRemaining());
        $this->assertSame(4, $meld->getCardsRemaining());
    }

    public function testAddToOthersMeldRunNoMock(): void
    {
        $otherHand = new GinRummyHand();
        $otherHand->add(new StandardPlayingCard("clubs", 3));
        $otherHand->add(new StandardPlayingCard("hearts", 3));
        $otherHand->add(new StandardPlayingCard("diamonds", 3));
        $otherHand->add(new StandardPlayingCard("diamonds", 4));
        $otherHand->add(new StandardPlayingCard("diamonds", 5));
        $otherHand->add(new StandardPlayingCard("diamonds", 6));
        $otherHand->add(new StandardPlayingCard("hearts", 6));
        $otherHand->add(new StandardPlayingCard("clubs", 10));
        $otherHand->add(new StandardPlayingCard("diamonds", 13));
        $otherHand->add(new StandardPlayingCard("clubs", 11));
        $meld = new Meld("run");
        $otherHand->addMeld($meld);
        $otherHand->addToMeld(3, 0);
        $otherHand->addToMeld(4, 0);
        $otherHand->addToMeld(5, 0);

        $thisHand = new GinRummyHand();
        $thisHand->add(new StandardPlayingCard("diamonds", 7));

        $this->scoring->addToOthersMeld("diamonds", 7, $thisHand, $otherHand);

        $this->assertSame(0, $thisHand->getCardsRemaining());
        $this->assertSame(11, $otherHand->getCardsRemaining());
        $this->assertSame(4, $meld->getCardsRemaining());
    }

    public function testAddToOthersMeldInvalidNoMock(): void
    {
        $otherHand = new GinRummyHand();
        $otherHand->add(new StandardPlayingCard("clubs", 3));
        $otherHand->add(new StandardPlayingCard("hearts", 3));
        $otherHand->add(new StandardPlayingCard("diamonds", 3));
        $otherHand->add(new StandardPlayingCard("diamonds", 4));
        $otherHand->add(new StandardPlayingCard("diamonds", 5));
        $otherHand->add(new StandardPlayingCard("diamonds", 6));
        $otherHand->add(new StandardPlayingCard("hearts", 6));
        $otherHand->add(new StandardPlayingCard("clubs", 10));
        $otherHand->add(new StandardPlayingCard("diamonds", 13));
        $otherHand->add(new StandardPlayingCard("clubs", 11));
        $meld = new Meld("run");
        $otherHand->addMeld($meld);
        $otherHand->addToMeld(3, 0);
        $otherHand->addToMeld(4, 0);
        $otherHand->addToMeld(5, 0);

        $thisHand = new GinRummyHand();
        $thisHand->add(new StandardPlayingCard("diamonds", 8));

        $this->scoring->addToOthersMeld("diamonds", 8, $thisHand, $otherHand);

        $this->assertSame(1, $thisHand->getCardsRemaining());
        $this->assertSame(10, $otherHand->getCardsRemaining());
        $this->assertSame(3, $meld->getCardsRemaining());
    }

    public function testCheckScoreDiffNeg(): void
    {
        $unmatched = [
            $this->cards[0],
            $this->cards[1]
        ];
        $this->hand->
            method('getUnmatched')->
            willReturn($unmatched);
        $player = $this->createStub(Player::class);
        $player->
            method('getHand')->
            willReturn($this->hand);
        $opponent = $this->createStub(Player::class);
        $game = $this->createStub(Game::class);
        $game->
            method('score')->
            willReturn(-1);

        $flash = $this->scoring->checkScoreDiff($player, $opponent, $game);

        $this->assertEquals($flash, " Motståndaren vinner och får 1 poäng.");
    }

    public function testCheckScoreDiffPos(): void
    {
        $unmatched = [
            $this->cards[0],
            $this->cards[1]
        ];
        $this->hand->
            method('getUnmatched')->
            willReturn($unmatched);
        $player = $this->createStub(Player::class);
        $player->
            method('getHand')->
            willReturn($this->hand);
        $opponent = $this->createStub(Player::class);
        $game = $this->createStub(Game::class);
        $game->
            method('score')->
            willReturn(1);

        $flash = $this->scoring->checkScoreDiff($player, $opponent, $game);

        $this->assertEquals($flash, " Du vinner och får 1 poäng.");
    }

    public function testCheckScoreDiffEqual(): void
    {
        $unmatched = [];
        $this->hand->
            method('getUnmatched')->
            willReturn($unmatched);
        $player = $this->createStub(Player::class);
        $player->
            method('getHand')->
            willReturn($this->hand);
        $opponent = $this->createStub(Player::class);
        $game = $this->createStub(Game::class);
        $game->
            method('score')->
            willReturn(10);

        $flash = $this->scoring->checkScoreDiff($player, $opponent, $game);

        $this->assertEquals($flash, " Du har gin och får 10 poäng.");
    }
}
