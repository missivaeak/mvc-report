<?php

namespace App\Game;

use App\Game\Meld;

use App\Errors\InvalidMeldTypeException;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för Meld
 */
final class MeldTest extends TestCase
{
    private Meld $setMeld;
    private Meld $runMeld;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $this->setMeld = new Meld("set");
        $this->runMeld = new Meld("run");
    }

    /**
     * Testar instansiering
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Game\Meld", $this->setMeld);
    }

    /**
     * Testar att exception kastas när
     * meld instansieras med fel parameter
     */
    public function testExceptionWrongInstansiate(): void
    {
        $this->expectException(InvalidMeldTypeException::class);
        new Meld("oooooo");
    }

    /**
     * Testar att isRun returnerar rätt värden
     */
    public function testIsRun(): void
    {
        $this->assertFalse($this->setMeld->isRun());
        $this->assertTrue($this->runMeld->isRun());
    }

    /**
     * Testar att isSet returnerar rätt värden
     */
    public function testIsSet(): void
    {
        $this->assertFalse($this->runMeld->isSet());
        $this->assertTrue($this->setMeld->isSet());
    }

    /**
     * Testar att getValue returnerar rätt värden
     */
    public function testGetValue(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);
        $card->method('getValue')->willReturn(4);

        $this->setMeld->add($card);

        $value = $this->setMeld->getValue();
        $this->assertSame(4, $value);

        $this->assertNull($this->runMeld->getValue());
    }

    /**
     * Testar att getSuit returnerar rätt värden
     */
    public function testGetSuit(): void
    {
        $card = $this->createStub(StandardPlayingCard::class);
        $card->method('getSuit')->willReturn("hearts");

        $this->runMeld->add($card);

        $suit = $this->runMeld->getSuit();
        $this->assertSame("hearts", $suit);

        $this->assertNull($this->setMeld->getSuit());
    }
}
