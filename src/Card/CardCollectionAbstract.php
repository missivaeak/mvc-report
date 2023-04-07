<?php

namespace App\Card;

use App\Card\CardInterface;

class CardCollectionAbstract
{
    protected array $cards;

    private string $emptyWarning = "Inga kort kvar.";

    public function __construct()
    {
        $this->cards = [];
    }

    public function add(CardInterface $card)
    {
        $this->cards[] = $card;
    }

    public function peekAllCards(): array
    {
        $cards = [];
        foreach ($this->cards as $card) {
            $cards[] = $card->peek();
        }
        return $cards;
    }

    public function getEmptyWarning(): string
    {
        return $this->emptyWarning;
    }

    public function getCardsRemaining(): int
    {
        return count($this->cards);
    }

    public function shuffle()
    {
        shuffle($this->cards);
    }

    public function draw(): CardInterface
    {
        return array_pop($this->cards);
    }
}
