<?php

namespace App\Game;

use App\Game\Player;

class Round
{
    private array $players;
    private Player $dealer;
    private bool $firstRound;
    
    public function __construct(Player $player, Player $opponent)
    {
        $this->players = [
            $player,
            $opponent
        ];
        $this->firstRound = false;
    }

    public function randomiseDealer(): Player
    {
        $dealer = $this->players[array_rand($this->players)];
        $this->dealer = $dealer;

        return $dealer;
    }

    public function getDealer(): Player
    {
        return $this->dealer;
    }

    public function setDealer(Player $dealer)
    {
        $this->dealer = $dealer;
    }

    public function getNextDealer(): Player
    {
        foreach ($this->players as $player) {
            if ($player !== $this->dealer) {
                return $player;
            }
        }
    }

    public function setFirstRound(): void
    {
        $this->firstRound = true;
    }

    public function isFirstRound(): bool
    {
        return $this->firstRound;
    }
}
