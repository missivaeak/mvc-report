<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardCollectionAbstract;
use App\Card\CardInterface;

class Hand extends CardCollectionAbstract
{
    public function revealAll()
    {
        foreach ($this->cards as $card) {
            $card->reveal();
        }
    }

    public function hideAll()
    {
        foreach ($this->cards as $card) {
            $card->hide();
        }
    }
}
