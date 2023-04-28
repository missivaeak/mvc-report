<?php

namespace App\Game;

use App\Game\Round;
use App\Game\Player;
use App\Game\Discard;
use App\Game\StandardPlayingCardsDeck;

/**
 * Represents the state and pariticipating objects of a Gin Rummy game
 */
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

    /**
     * Constructor
     * 
     * @param Player $player The human player
     * @param Player $opponent Computer controlled player
     * @param StandardPlayingCardsDeck $deck Deck of cards
     * @param Discard $discard The discard pile
     */
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

    /**
     * Get human player
     * 
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Get computer controlled opponent
     * 
     * @return Player
     */
    public function getOpponent(): Player
    {
        return $this->opponent;
    }

    /**
     * Get the score threshold to reach to win the game
     * 
     * @return int
     */
    public function getWinThreshold(): int
    {
        return $this->winThreshold;
    }

    /**
     * Get hand score threshold to knock
     * 
     * @return int
     */
    public function getKnockThreshold(): int
    {
        return $this->knockThreshold;
    }

    /**
     * Get score bonus to go gin
     * 
     * @return int
     */
    public function getGinBonus(): int
    {
        return $this->ginBonus;
    }

    /**
     * Get score bonus for undercutting
     * 
     * @return int
     */
    public function getUndercutBonus(): int
    {
        return $this->undercutBonus;
    }

    /**
     * Get the score bonus to knock
     * 
     * @return int
     */
    public function getKnockBonus(): int
    {
        return $this->knockBonus;
    }

    /**
     * Get hand size limit
     * 
     * @return int
     */
    public function getHandSize(): int
    {
        return $this->handSize;
    }

    /**
     * Deal cards to both players up to hand size
     */
    public function deal(): void
    {
        for ($i = 0; $i < $this->handSize; $i++) {
            $this->round->deal($this->deck);
        }
    }

    /**
     * Puts a card from the deck in the discard pile
     */
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

    /**
     * Return cards from both players hands and the discard pile
     */
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

    /**
     * Sets round
     * 
     * @param Round $round A round to set as the current round for the game
     */
    public function setRound(Round $round): void
    {
        $this->round = $round;
    }

    /**
     * Get the human players hand
     * 
     * @return GinRummyHand
     */
    public function getPlayerHand(): GinRummyHand
    {
        return $this->player->getHand();
    }

    /**
     * Get the computer opponent players hand
     * 
     * @return GinRummyHand
     */
    public function getOpponentHand(): GinRummyHand
    {
        return $this->opponent->getHand();
    }

    /**
     * Get the deck
     * 
     * @return StandardPlayingCardsDeck
     */
    public function getDeck(): StandardPlayingCardsDeck
    {
        return $this->deck;
    }

    /**
     * Get the discard pile
     * 
     * @return Discard
     */
    public function getDiscard(): Discard
    {
        return $this->discard;
    }

    /**
     * Get the current round
     * 
     * @return Round
     */
    public function getRound(): Round
    {
        return $this->round;
    }

    /**
     * Give score at the end of a round, including knock bonus and undercut bonus
     * 
     * @param Player $knocking Knocking player
     * @param Player $notKnocking The player who's not knocking
     * @param int $difference The difference in score, positive means the knocking player's advantage and negative is the not knocking player's advantage.
     */
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
