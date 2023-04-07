<?php

namespace App\Card;

use App\Card\CardInterface;

class Card implements CardInterface
{
    private string $value;
    private bool $faceUp;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->faceUp = false;
    }

    public function hide()
    {
        $this->faceUp = false;
    }

    public function reveal()
    {
        $this->faceUp = true;
    }

    public function look(): ?string
    {
        if ($this->faceUp) {
            return $this->value;
        } else {
            return null;
        }
    }

    public function peek(): string
    {
        return $this->value;
    }
}
