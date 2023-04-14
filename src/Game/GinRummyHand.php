<?php

namespace App\Game;

use App\Game\CardCollectionAbstract;
use App\Game\StandardPlayingCard;
use App\Game\Meld;

class GinRummyHand extends CardCollectionAbstract
{
    /** @var array<Meld> */
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

    /** @return array<Meld> */
    public function getMelds(): array
    {
        return $this->melds;
    }

    /** @return ?array<StandardPlayingCard> */
    public function getMeld(int $index): ?array
    {
        if ($this->melds[$index]) {
            return $this->melds[$index]->getAllCards();
        }
        return null;
    }

    /** @return array<StandardPlayingCard> */
    public function getUnmatched(): array
    {
        $melded = [];

        foreach ($this->melds as $meld) {
            foreach ($meld->getAllCards() as $card) {
                $melded[] = $card;
            }
        }

        $unmatched = array_diff($this->cards, $melded);

        return array_values($unmatched);
    }

    public function addMeld(): int
    {
        $index = count($this->melds);
        $this->melds[] = new Meld;

        return $index;
    }

    public function addToMeld(int $unmatchedIndex, int $meldIndex): StandardPlayingCard
    {
        $unmatched = $this->getUnmatched();
        $card = $unmatched[$unmatchedIndex];
        $this->melds[$meldIndex]->add($card);

        return $card;
    }
}
