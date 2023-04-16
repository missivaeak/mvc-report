<?php

namespace App\Game;

use App\Game\CardCollectionAbstract;

class Discard extends CardCollectionAbstract
{
    public function getTopCard(): ?CardInterface
    {
        $result = end($this->cards);
        if ($result === false) {
            return null;
        }
        return $result;
    }
}
