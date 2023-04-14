<?php

namespace App\Game;

use App\Game\GinRummyHand;

class Player
{
    private GinRummyHand $hand;
    private int $score;

    public function __construct(GinRummyHand $hand)
    {
        $this->hand = $hand;
        $this->score = 0;
    }
}
