<?php

namespace App\Game;

use App\Game\StandardPlayingCardsTrait;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsvit för StandardPlayingCardTrait
 */
final class StandardPlayingCardsTraitTest extends TestCase
{
    /**
     * Testar getAllValidCardValues
     */
    public function testGetAllValidCardValues(): void
    {
        $mock = $this->getMockForTrait(StandardPlayingCardsTrait::class);
        $cardValues = $mock->getAllValidCardValues();

        $this->assertIsArray($cardValues);
        $this->assertCount(52, $cardValues);
        $this->assertCount(2, $cardValues[0]);
        $this->assertArrayHasKey("suit", $cardValues[0]);
        $this->assertArrayHasKey("value", $cardValues[0]);
        $this->assertIsString($cardValues[0]["suit"]);
        $this->assertIsInt($cardValues[0]["value"]);
    }
}
