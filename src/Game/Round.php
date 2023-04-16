<?php

namespace App\Game;

use App\Game\Player;

use TypeError;

class Round
{
    private array $players;
    private Player $dealer;
    private int $turn;
    private int $turnStep;

    public function __construct(Player $player, Player $opponent)
    {
        $this->players = [
            $player,
            $opponent
        ];
        $this->turn = 0;
        $this->turnStep = 0;
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

    public function isFirstRound(): bool
    {
        return $this->turn == 0;
    }

    public function nextTurn(): void
    {
        $this->turn++;
        $this->turnStep = 0;
    }

    public function nextStep(): void
    {
        $this->turnStep++;
    }

    public function deal(CardCollectionAbstract $deck): bool
    {
        foreach ($this->players as $player) {
            try {
                $player->getHand()->add($deck->draw());
            } catch (TypeError $e) {
                return false;
            }
        }

        return true;
    }
}
