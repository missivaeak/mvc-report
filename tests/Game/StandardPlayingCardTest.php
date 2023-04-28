<?php

namespace App\Game;

use App\Game\StandardPlayingCard;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för Discard
 */
final class StandardPlayingCardTest extends TestCase
{
    private StandardPlayingCard $card;

    /**
     * Setup för testfallen
     */
    protected function setUp(): void
    {
        $this->card = new StandardPlayingCard("diamonds", 4);
    }

    /**
     * Testar instansiering
     */
    public function testInstansiate(): void
    {
        $this->assertInstanceOf("App\Game\StandardPlayingCard", $this->card);
    }

    /**
     * Testar getValue
     */
    public function testGetValue(): void
    {
        $this->assertSame(4, $this->card->getValue());
    }

    /**
     * Testar getSuit
     */
    public function testGetSuit(): void
    {
        $this->assertSame("diamonds", $this->card->getSuit());
    }

    /**
     * Testar getFace
     */
    public function testGetFace(): void
    {
        $this->assertSame("diamonds-04", $this->card->getFace());
    }

    /**
     * Testar toString
     */
    public function testToString(): void
    {
        $this->assertSame("diamonds-04", strval($this->card));
    }

    /**
     * Testar att isFaceUp ger false på nyskapat objekt
     */
    public function testIsFaceUp(): void
    {
        $this->assertFalse($this->card->isFaceUp());
    }

    /**
     * Testar att isFaceUp ger true efter reveal
     */
    public function testReveal(): void
    {
        $this->card->reveal();
        $this->assertTrue($this->card->isFaceUp());
    }

    /**
     * Testar att isFaceUp ger false efter reveal och hide
     */
    public function testHide(): void
    {
        $this->card->reveal();
        $this->card->hide();
        $this->assertFalse($this->card->isFaceUp());
    }
}
