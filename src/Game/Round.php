<?php

namespace App\Game;

use App\Game\Player;

use TypeError;
use Exception;

class Round
{
    private array $players;
    private Player $dealer;
    private Player $activePlayer;
    private int $turn;
    /**
     * Motsvarar olika steg på en tur
     * 0 = välja kortlek eller slänghög,
     * 1 = slänga kort från handen,
     * 2 = bestämma om man vill avsluta turen eller knacka,
     * 3 = matcha kort på motståndarens serier,
     * 4 = välja kortet på slänghögen eller pass (första draget),
     * 5 = välja kortet på slänghögen eller pass (andra draget),
     * 6 = tvungen att ta översta kortet på leken (om båda passade)
     * @var int turnStep
     */
    private int $turnStep;

    public function __construct(Player $player, Player $opponent)
    {
        $this->players = [
            $player,
            $opponent
        ];
        $this->turn = 0;
        $this->turnStep = 4;
    }

    public function randomiseDealer(): Player
    {
        $dealerIndex = array_rand($this->players);
        $activePlayerIndex = ($dealerIndex + 1) % 2;
        // throw new Exception($dealerIndex.$activePlayerIndex);
        $this->dealer = $this->players[$dealerIndex];
        $this->activePlayer = $this->players[$activePlayerIndex];

        return $this->dealer;
    }

    public function getDealer(): Player
    {
        return $this->dealer;
    }

    public function setDealer(Player $dealer)
    {
        $this->dealer = $dealer;
    }

    public function getActivePlayer(): Player
    {
        return $this->activePlayer;
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
        foreach ($this->players as $player) {
            if ($player !== $this->activePlayer) {
                $this->activePlayer = $player;
            }
        }
    }

    public function getStep(): int
    {
        return $this->turnStep;
    }

    public function setStep(int $turnStep): void
    {
        $this->turnStep = $turnStep;
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
