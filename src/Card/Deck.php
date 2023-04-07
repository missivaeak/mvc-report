<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardCollectionAbstract;

class Deck extends CardCollectionAbstract
{
    private int $values = 13;

    private array $suits = [
        "hearts",
        "diamonds",
        "clubs",
        "spades"
    ];

    public function __construct()
    {
        foreach ($this->suits as $suit) {
            for ($value = 1; $value <= $this->values; $value++) {
                $card = $suit . '-' . sprintf("%02d", $value);
                $this->cards[] = new Card($card);
            }
        }
    }
}
