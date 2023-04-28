<?php

namespace App\Game;

use App\Game\Player;

use TypeError;
use Exception;

class Round
{
    /** @var array<Player> */
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
     * 7 = stopp innan rundan är slut
     * @var int step
     */
    private int $step;

    public function __construct(Player $player, Player $opponent)
    {
        $this->players = [
            $player,
            $opponent
        ];
        $this->turn = 0;
        $this->step = 4;
    }

    public function randomiseDealer(): Player
    {
        $dealerIndex = intval(array_rand($this->players));
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

    public function setDealer(Player $dealer): void
    {
        $this->dealer = $dealer;
    }

    public function getActivePlayer(): Player
    {
        return $this->activePlayer;
    }

    public function autoSetActivePlayer(): void
    {
        foreach ($this->players as $player) {
            if ($player !== $this->dealer) {
                $this->activePlayer = $player;
                return;
            }
        }
    }

    public function getNextDealer(): ?Player
    {
        foreach ($this->players as $player) {
            if ($player !== $this->dealer) {
                return $player;
            }
        }
        return null;
    }

    public function nextTurn(): void
    {
        $this->turn++;
        foreach ($this->players as $player) {
            if ($player !== $this->activePlayer) {
                $this->activePlayer = $player;
                return;
            }
        }
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function setStep(int $step): void
    {
        $this->step = $step;
    }

    public function nextStep(): void
    {
        $this->step++;
    }

    public function deal(CardCollectionAbstract $deck): bool
    {
        $result = false;
        foreach ($this->players as $player) {
            $card = $deck->draw();
            if ($card) {
                $player->getHand()->add($card);
                $result = true;
            }
        }

        return $result;
    }
}
