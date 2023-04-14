<?php

namespace App\Game;

use App\Game\StandardPlayingCard;
use App\Game\CardCollectionAbstract;
use App\Game\StandardPlayingCardsTrait;

class StandardPlayingCardsDeck extends CardCollectionAbstract
{
    use StandardPlayingCardsTrait;
}
