<?php

namespace App\Game;

use App\Game\GinRummyHand;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

use OutOfRangeException;

/**
 * Testsvit för GinRummyHand
 */
final class GinRummyHandTest extends TestCase
{
    private GinRummyHand $emptyHand;
    private GinRummyHand $nonEmptyHand;
    private Meld $meld;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $this->emptyHand = new GinRummyHand();

        $this->nonEmptyHand = new GinRummyHand();
        $this->meld = $this->createStub(Meld::class);
        $this->nonEmptyHand->addMeld($this->meld);
        for ($i = 0; $i < 10; $i++) {
            $card = $this->createStub(StandardPlayingCard::class);
            $this->nonEmptyHand->add($card);
        }

    }

    /**
     * Testar instansiering
     */
    public function testInstantiate(): void
    {
        $this->assertInstanceOf("App\Game\GinRummyHand", $this->emptyHand);
    }

    /**
     * Testar att getMelds ger tom array från tom hand
     */
    public function testGetMeldsEmpty(): void
    {
        $gottenMelds = $this->emptyHand->getMelds();

        $this->assertIsArray($gottenMelds);
        $this->assertCount(0, $gottenMelds);
    }

    /**
     * Testar att getMelds ger alla melds från icke-tom hand
     */
    public function testGetMeldsNonEmpty(): void
    {
        $gottenMelds = $this->nonEmptyHand->getMelds();

        $this->assertIsArray($gottenMelds);
        $this->assertCount(1, $gottenMelds);
        $this->assertContainsOnlyInstancesOf("App\Game\Meld", $gottenMelds);
    }

    /**
     * Testar att getMeldCards kallar på getCards() på melds i handen
     */
    public function testGetMeldCards(): void
    {
        $meld = $this->meld;
        $meld/** @scrutinizer ignore-call */
            ->expects($this->once())->method('getCards');

        $this->nonEmptyHand->getMeldCards(0);
    }

    /**
     * Testar att getMelds kastar ett exception på ett felaktigt index
     */
    public function testGetMeldCardsWrongIndex(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->nonEmptyHand->getMeldCards(6);
    }

    /**
     * Testar att getUnmatched inte får med kort i en meld
     */
    public function testGetUnmatched(): void
    {
        $cards = $this->nonEmptyHand->getCards();
        $meldCards = [
            $cards[0],
            $cards[1],
            $cards[2]
        ];

        $meld = $this->meld;
        $meld->expects($this->once())
            ->method('getCards')
            ->willReturn($meldCards);

        $unmatched = $this->nonEmptyHand->getUnmatched();
        foreach ($meldCards as $card) {
            $this->assertNotContains($card, $unmatched);
        }
    }

    /**
     * Testar att addToMeld kallar på add() på rätt kort
     */
    public function testAddToMeld(): void
    {
        $unmatched = $this->nonEmptyHand->getUnmatched();
        $card = $unmatched[0];

        $meld = $this->meld;
        $meld->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($card));

        $this->nonEmptyHand->addToMeld(0, 0);
    }

    /**
     * Testar att resetMelds gör att melden försvinner ur
     * getMelds
     */
    public function testResetMelds(): void
    {
        $this->nonEmptyHand->resetMelds();
        $melds = $this->nonEmptyHand->getMelds();
        $this->assertNotContains($this->meld, $melds);
    }
}
