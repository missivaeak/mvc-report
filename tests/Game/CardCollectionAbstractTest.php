<?php

namespace App\Game;

use App\Game\CardCollectionAbstract;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;
use PHPUnit\Framework\MockObject\Stub;

/**
 * Testsvit för CardCollectionAbstract
 */
final class CardCollectionAbstractTest extends TestCase
{
    private CardCollectionAbstract $emptyCollection;
    private CardCollectionAbstract $nonEmptyCollection;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $this->emptyCollection = $this->getMockForAbstractClass(CardCollectionAbstract::class);

        $this->nonEmptyCollection = $this->getMockForAbstractClass(CardCollectionAbstract::class);
        for ($i = 0; $i < 7; $i++) {
            $card = $this->createStub(StandardPlayingCard::class);
            $this->nonEmptyCollection->add($card);
        }
    }

    /**
     * Testar att instansieringen gick bra
     */
    public function testInstantiate(): void
    {
        $this->assertInstanceOf("App\Game\CardCollectionAbstract", $this->emptyCollection);
    }

    /**
     * Testar att nyligen skapat objekt returnerar 0
     * på metoden getCardRemaining()
     */
    public function testZeroCards(): void
    {
        $amount = $this->emptyCollection->getCardsRemaining();

        $this->assertIsInt($amount);
        $this->assertEquals(0, $amount);
    }

    /**
     * Testar att lägga till ett kort med add()
     * assertar genom getCardsRemaining()
     */
    public function testAddCard(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);
        $this->emptyCollection->add($card);

        $this->assertSame(1, $this->emptyCollection->getCardsRemaining());
    }

    /**
     * Testar att getCardsRemaining() returnerar
     * rätt värde i en icke-tom collection
     */
    public function testNonZeroCards(): void
    {
        $amount = $this->nonEmptyCollection->getCardsRemaining();

        $this->assertIsInt($amount);
        $this->assertEquals(7, $amount);
    }

    /**
     * Testar getCards() på en tom collection
     */
    public function testGetCardsEmpty(): void
    {
        $getCardsEmpty = $this->emptyCollection->getCards();
        $this->assertIsArray($getCardsEmpty);
        $this->assertContainsOnlyInstancesOf("App\Game\CardInterface", $getCardsEmpty);
        $this->assertCount(0, $getCardsEmpty);
    }

    /**
     * Testar getCards() på en icke-tom collection
     */
    public function testGetCardsNonEmpty(): void
    {
        $getCardsNonEmpty = $this->nonEmptyCollection->getCards();
        $this->assertIsArray($getCardsNonEmpty);
        $this->assertContainsOnlyInstancesOf("App\Game\CardInterface", $getCardsNonEmpty);
        $this->assertCount(7, $getCardsNonEmpty);
    }

    /**
     * Testar getFaces() på en tom collection
     */
    public function testGetFacesEmpty(): void
    {
        $getFacesEmpty = $this->emptyCollection->getFaces();
        $this->assertIsArray($getFacesEmpty);
        $this->assertContainsOnlyInstancesOf("string", $getFacesEmpty);
        $this->assertCount(0, $getFacesEmpty);
    }

    /**
     * Testar getFaces() på en icke-tom collection
     */
    public function testGetFacesNonEmpty(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            $card = $this->createStub(StandardPlayingCard::class);
            $card->method("getFace")->willReturn("hearts-" . sprintf("%02d", $i));
            $this->emptyCollection->add($card);
        }

        $getFacesNonEmpty = $this->emptyCollection->getFaces();
        $this->assertIsArray($getFacesNonEmpty);
        $this->assertContainsOnly("string", $getFacesNonEmpty);
        $this->assertCount(7, $getFacesNonEmpty);
        for ($i = 1; $i <= 7; $i++) {
            $face = $getFacesNonEmpty[$i - 1];
            $this->assertSame($face, "hearts-" . sprintf("%02d", $i));
        }
    }

    /**
     * Testar att getEmptyWarning() returnerar en sträng
     */
    public function testGetEmptyWarning(): void
    {
        $this->assertIsString($this->emptyCollection->getEmptyWarning());
    }

    /**
     * Testar att revealAll() kallar reveal() på kort i collectionen
     */
    public function testRevealAll(): void
    {
        $card = $this->createMock(StandardPlayingCard::class);

        $card->expects($this->once())
            ->method('reveal');

        $this->emptyCollection->add($card);
        $this->emptyCollection->revealAll();
    }

    /**
     * Testar att hideAll() kallar hide() på kort i collectionen
     */
    public function testHideAll(): void
    {
        $card = $this->createMock(StandardPlayingCard::class);

        $card->expects($this->once())
            ->method('hide');

        $this->emptyCollection->add($card);
        $this->emptyCollection->hideAll();
    }

    /**
     * Testar att shuffle ger skilt resultat på getCards()
     * minst en gång när shuffle() blivit kallat 10 gånger
     */
    public function testShuffle(): void
    {
        $unshuffled = $this->nonEmptyCollection->getCards();

        $notSameFlag = false;

        for ($i = 0; $i < 10; $i++) {
            $this->nonEmptyCollection->shuffle();
            $shuffled = $this->nonEmptyCollection->getCards();

            if ($unshuffled !== $shuffled) {
                $notSameFlag = true;
            }
        }

        $this->assertTrue($notSameFlag);
    }

    /**
     * Testar att draw() ger null på en tom collection
     */
    public function testDrawEmpty(): void
    {
        $card = $this->emptyCollection->draw();

        $this->assertNull($card);
    }

    /**
     * Testar att draw() ger ett StandardPlayingCard i en icke-tom collection
     */
    public function testDrawNonEmpty(): void
    {
        $card = $this->nonEmptyCollection->draw();

        $this->assertInstanceOf("App\Game\StandardPlayingCard", $card);
    }

    /**
     * Testar att drawByPattern() ger null i en tom collection
     */
    public function testDrawByPatternEmpty(): void
    {
        $card = $this->emptyCollection->drawByPattern("hearts", 5);

        $this->assertNull($card);
    }

    /**
     * Testar att drawByPattern() ger rätt kort i en
     * icke-tom collection
     */
    public function testDrawByPatternNonEmpty(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);
        $card->method('getValue')->willReturn(5);
        $card->method('getSuit')->willReturn("hearts");

        $this->emptyCollection->add($card);

        $drawnCard = $this->emptyCollection->drawByPattern("hearts", 5);

        $this->assertSame($card, $drawnCard);
    }
}
