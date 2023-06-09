<?php

namespace App\Game;

use App\Game\CardInterface;

abstract class CardCollectionAbstract
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
     * @return array<CardInterface>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @return array<string>
     */
    public function getFaces(): array
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

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function draw(): ?CardInterface
    {
        return array_pop($this->cards);
    }

    public function drawByPattern(string $suit, int $value): ?CardInterface
    {
        $card = $this->getByPattern($suit, $value);

        $index = intval(array_search($card, $this->cards));
        unset($this->cards[$index]);

        return $card;
    }

    public function getByPattern(string $suit, int $value): ?CardInterface
    {
        foreach ($this->cards as $key => $card) {
            if ($card->getSuit() === $suit && $card->getValue() === $value) {
                return $card;
            }
        }

        return null;
    }
}
