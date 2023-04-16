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
        parent::__construct();
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
    public function getMeldCards(int $index): ?array
    {
        if ($this->melds[$index]) {
            return $this->melds[$index]->getCards();
        }
        return null;
    }

    /** @return array<StandardPlayingCard> */
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

    public function addToMeld(int $unmatchedIndex, int $meldIndex): StandardPlayingCard
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

    public function drawByPattern(string $suit, int $value): ?CardInterface
    {
        $card = null;
        $cardPattern = new StandardPlayingCard($suit, $value);
        $result = array_search($cardPattern, $this->cards);
        if (gettype($result) == "integer") {
            $card = $this->cards[$result];
            unset($this->cards[$result]);
        }

        return $card;
    }
}
