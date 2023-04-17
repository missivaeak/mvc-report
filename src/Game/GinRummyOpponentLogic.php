<?php

namespace App\Game;

use App\Game\Player;
use App\Game\Discard;
use App\Game\StandardPlayingCardsDeck;
use App\Game\CardInterface;
use App\Game\GinRummyScoring;
use Exception;

// use App\Game\StandardPlayingCardsTrait

class GinRummyOpponentLogic
{
    private Player $opponent;
    private StandardPlayingCardsDeck $deck;
    private Discard $discard;
    private GinRummyScoring $scoring;
    private int $knockThreshold;

    public function __construct(
        Game $game,
        GinRummyScoring $scoring
    ) {
        $this->opponent = $game->getOpponent();
        $this->deck = $game->getDeck();
        $this->discard = $game->getDiscard();
        $this->scoring = $scoring;
        $this->knockThreshold = $game->getKnockThreshold();
    }

    public function pickDiscard(): CardInterface
    {
        $discard = $this->discard;
        $hand = $this->opponent->getHand();
        $card = $discard->draw();
        $card->hide();
        $hand->add($card);

        return $card;
    }

    public function pickDeck(): CardInterface
    {
        $deck = $this->deck;
        $hand = $this->opponent->getHand();
        $card = $deck->draw();
        $card->hide();
        $hand->add($card);

        return $card;
    }

    public function discard(): ?CardInterface
    {
        $hand = $this->opponent->getHand();

        $hand->resetMelds();
        $this->scoring->meld($hand);
        $unmatched = $hand->getUnmatched();

        $card = $unmatched[array_rand($unmatched)];
        foreach ($unmatched as $unmatchedCard) {
            if ($card->getValue() < $unmatchedCard->getValue()){
                $card = $unmatchedCard;
            }
        }
        $hand->drawByPattern($card->getSuit(), $card->getValue());
        $card->reveal();
        $this->discard->add($card);

        $hand->resetMelds();
        $this->scoring->meld($hand);

        return $card;
    }

    /**
     * Väljer om motståndaren ska dra från slänghögen eller passa
     * @return ?CardInterface Kortet som motståndaren drar från slänghögen
     */
    public function drawOrPass(): ?CardInterface
    {
        if (random_int(0, 1) === 0) {
            $result = $this->pickDiscard();
            return $result;
        }

        return null;
    }

    /**
     * Väljer om motståndaren ska dra från slänghögen eller passa
     * @return ?CardInterface Kortet som motståndaren drar från slänghögen
     */
    public function drawDeckOrDrawDiscard(): ?CardInterface
    {
        if (random_int(0, 4) === 0) {
            $result = $this->pickDiscard();
            return $result;
        }

        $this->pickDeck();
        return null;
    }

    public function knockOrPass(): bool
    {
        $hand = $this->opponent->getHand();
        $score = $this->scoring->handScore($hand);
        if ($score < $this->knockThreshold) {
            return true;
        }
        return false;
    }
}
