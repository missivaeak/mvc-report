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

    public function deal(): void
    {
        for ($i = 0; $i < $this->handSize; $i++) {
            $this->round->deal($this->deck);
        }
    }

    public function deckToDiscard(): bool
    {
        $card = $this->deck->draw();
        if ($card) {
            $card->reveal();
            $this->discard->add($card);
            return true;
        }
        return false;
    }

    public function returnCards(): void
    {
        $playerHand = $this->player->getHand();
        $opponentHand = $this->opponent->getHand();
        $discard = $this->discard;
        $myList = [$playerHand, $opponentHand, $discard];
        foreach ($myList as $cards) {
            $length = $cards->getCardsRemaining();
            for ($i = 0; $i < $length; $i++) {
                $card = $cards->draw();
                if ($card) {
                    $this->deck->add($card);
                }
            }
        }
    }

    public function setRound(Round $round): void
    {
        $this->round = $round;
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

    public function score(Player $knocking, Player $notKnocking, int $difference): int
    {
        if ($difference > 0) {
            $amount = $difference + $this->knockBonus;
            $knocking->addScore($amount);
            return $amount;
        } elseif ($difference < 0) {
            $amount = abs($difference) + $this->undercutBonus;
            $notKnocking->addScore($amount);
            return 0 - $amount;
        }

        return 0;
    }
}
