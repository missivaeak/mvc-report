<?php

namespace App\Game;

use App\Game\Discard;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för Discard
 */
final class DiscardTest extends TestCase
{
    private Discard $emptyDiscard;
    private Discard $nonEmptyDiscard;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $this->emptyDiscard = new Discard;

        $this->nonEmptyDiscard = new Discard;
        for ($i = 0; $i < 7; $i++) {
            $card = $this->createStub(StandardPlayingCard::class);
            $this->nonEmptyDiscard->add($card);
        }
    }

    /**
     * Testar att getTopCard ger null i en tom discard
     */
    public function testGetTopCardEmpty(): void
    {
        $topCard = $this->emptyDiscard->getTopCard();

        $this->assertNull($topCard);
    }

    /**
     * Testar att getTopCard ger rätt kort i en icke-tom discard
     */
    public function testGetTopCardNonEmpty(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);
        $topCard = $this->nonEmptyDiscard->getTopCard();

        $this->assertNotSame($card, $topCard);

        $this->nonEmptyDiscard->add($card);

        $topCard = $this->nonEmptyDiscard->getTopCard();

        $this->assertSame($card, $topCard);
    }
}
