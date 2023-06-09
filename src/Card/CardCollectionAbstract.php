<?php

namespace App\Card;

use App\Card\CardInterface;

class CardCollectionAbstract
{
    /**
     * @var array<CardInterface>
     */
    protected array $cards;

    private string $emptyWarning = "Inga kort kvar.";

    public function __construct()
    {
        $this->cards = [];
    }

    public function add(CardInterface $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * @return array<string>
     */
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

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function draw(): ?CardInterface
    {
        return array_pop($this->cards);
    }
}
