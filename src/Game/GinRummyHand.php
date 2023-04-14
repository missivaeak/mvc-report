<?php

namespace App\Game;

use App\Game\CardCollectionAbstract;
use App\Game\StandardPlayingCard;

class GinRummyHand extends CardCollectionAbstract
{
    /** @var array<?array<StandardPlayingCard>> */
    private array $melds;

    public function __construct()
    {
        $this->melds = [];
    }

    public function revealAll(): void
    {
        foreach ($this->cards as $card) {
            $card->reveal();
        }
    }

    public function hideAll(): void
    {
        foreach ($this->cards as $card) {
            $card->hide();
        }
    }

    /** @return array<?array<StandardPlayingCard>> */
    public function getMelds(): array
    {
        return $this->melds;
    }

    /** @return ?array<StandardPlayingCard> */
    public function getMeld(int $index): array
    {
        if ($this->melds[$index]) {
            return $this->melds[$index];
        }
        return null;
    }

    /** @return array<StandardPlayingCard> */
    public function getUnmatched(): array
    {
        $unmatched = [];
        foreach ($this->melds as $meld) {
            foreach ($meld as $meldCard) {
                $unmatched = [...array_filter($this->cards, function($unmatchedCard) use($meldCard) {
                    return $meldCard !== $unmatchedCard;
                })];
            }
        }

        return $unmatched;
    }

    public function addMeld(): void
    {
        $this->melds[] = [];
    }

    public function addToMeld(int $unmatchedIndex, int $meldIndex): void
    {
        $unmatched = $this->getUnmatched();
        $this->melds[$meldIndex][] = $unmatched[$unmatchedIndex];
    }
}
