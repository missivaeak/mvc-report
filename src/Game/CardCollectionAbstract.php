<?php

namespace App\Game;

use App\Game\CardInterface;

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
     * @return array<array<CardInterface>>
     */
    public function getAllCards(): array
    {
        $cards = [];

        foreach ($this->cards as $card) {
            $cards[] = [
                "suit" => $card->getSuit(),
                "value" => $card->getValue()
            ];
        }

        return $cards;
    }

    /**
     * @return array<string>
     */
    public function getAllFaces(): array
    {
        $cards = [];

        foreach ($this->cards as $card) {
            $cards[] = $card->getFace();
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
