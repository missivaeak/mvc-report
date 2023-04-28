<?php

namespace App\Game;

use App\Game\CardCollectionAbstract;
use App\Game\Meld;

use OutOfRangeException;

class GinRummyHand extends CardCollectionAbstract
{
    /** @var array<Meld> */
    private array $melds;

    public function __construct()
    {
        parent::__construct();
        $this->melds = [];
    }

    /** @return array<Meld> */
    public function getMelds(): array
    {
        return $this->melds;
    }

    /** @return array<CardInterface> */
    public function getMeldCards(int $index): array
    {
        if (!array_key_exists($index, $this->melds)) {
            throw new OutOfRangeException("Invalid meld index");
        }
        return $this->melds[$index]->getCards();
    }

    /** @return array<CardInterface> */
    public function getUnmatched(): array
    {
        $melded = [];

        foreach ($this->melds as $meld) {
            foreach ($meld->getCards() as $card) {
                $melded[] = $card;
            }
        }

        $unmatched = array_diff($this->cards, $melded);

        return $unmatched;
    }

    public function addMeld(Meld $meld): int
    {
        $index = count($this->melds);
        $this->melds[] = $meld;

        return $index;
    }

    public function addToMeld(int $unmatchedIndex, int $meldIndex): CardInterface
    {
        $unmatched = $this->getUnmatched();
        $card = $unmatched[$unmatchedIndex];
        $this->melds[$meldIndex]->add($card);

        return $card;
    }

    public function resetMelds(): void
    {
        $this->melds = [];
    }
}
