<?php

namespace App\Game;

class StandardPlayingCard implements CardInterface
{
    private bool $faceUp;
    private string $suit;
    private int $value;
    private string $face;

    public function __construct(string $suit, int $value)
    {
        $this->faceUp = false;
        $this->suit = $suit;
        $this->value = $value;
        $this->face = $suit . '-' . sprintf("%02d", $value);
    }

    public function hide(): void
    {
        $this->faceUp = false;
    }

    public function reveal(): void
    {
        $this->faceUp = true;
    }

    public function isFaceUp(): bool
    {
        return $this->faceUp;
    }

    public function getFace(): string
    {
        return $this->face;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function __toString(): string
    {
        return $this->getFace();
    }
}
