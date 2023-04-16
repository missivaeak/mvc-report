<?php

namespace App\Game;

use App\Game\Round;
use App\Game\Player;
use App\Game\Discard;
use App\Game\StandardPlayingCardsDeck;

class Game
{
    private Player $player;
    private Player $opponent;
    private Round $round;
    private Discard $discard;
    private StandardPlayingCardsDeck $deck;
    private int $winThreshold;
    private int $knockThreshold;
    private int $ginBonus;
    private int $undercutBonus;
    private int $knockBonus;
    private int $handSize;

    public function __construct(
        Player $player,
        Player $opponent,
        StandardPlayingCardsDeck $deck,
        Discard $discard
    ) {
        $this->player = $player;
        $this->opponent = $opponent;
        $this->deck = $deck;
        $this->discard = $discard;

        $this->winThreshold = 100;
        $this->knockThreshold = 10;
        $this->ginBonus = 25;
        $this->undercutBonus = 25;
        $this->knockBonus = 0;
        $this->handSize = 10;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getOpponent(): Player
    {
        return $this->opponent;
    }

    public function getWinThreshold(): int
    {
        return $this->winThreshold;
    }

    public function getKnockThreshold(): int
    {
        return $this->knockThreshold;
    }

    public function getGinBonus(): int
    {
        return $this->ginBonus;
    }

    public function getUndercutBonus(): int
    {
        return $this->undercutBonus;
    }

    public function getKnockBonus(): int
    {
        return $this->knockBonus;
    }

    public function getHandSize(): int
    {
        return $this->handSize;
    }

    private function deal(): void
    {
        for ($i = 0; $i < $this->handSize; $i++) {
            $this->round->deal($this->deck);
        }
    }

    private function deckToDiscard(): void
    {
        $card = $this->deck->draw();
        $card->reveal();
        $this->discard->add($card);
    }

    public function startRound(Round $round): void
    {
        $this->round = $round;
        $this->deal();
        $this->deckToDiscard();
    }

    public function getPlayerHand(): GinRummyHand
    {
        return $this->player->getHand();
    }

    public function getOpponentHand(): GinRummyHand
    {
        return $this->opponent->getHand();
    }

    public function getDeck(): StandardPlayingCardsDeck
    {
        return $this->deck;
    }

    public function getDiscard(): Discard
    {
        return $this->discard;
    }

    public function getRound(): Round
    {
        return $this->round;
    }
}
