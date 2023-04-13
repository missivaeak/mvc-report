<?php

namespace App\Card;

use App\Card\CardCollectionAbstract;

class Hand extends CardCollectionAbstract
{
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
}
