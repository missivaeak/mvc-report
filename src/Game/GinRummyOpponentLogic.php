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

    public function pickDiscard(): ?CardInterface
    {
        $discard = $this->discard;
        $hand = $this->opponent->getHand();
        $card = $discard->draw();
        if ($card) {
            $card->hide();
            $hand->add($card);
            return $card;
        }

        return null;
    }

    public function pickDeck(): ?CardInterface
    {
        $deck = $this->deck;
        $hand = $this->opponent->getHand();
        $card = $deck->draw();
        if ($card) {
            $card->hide();
            $hand->add($card);
            return $card;
        }

        return null;
    }

    public function discard(): ?CardInterface
    {
        $hand = $this->opponent->getHand();

        $hand->resetMelds();
        $this->scoring->meld($hand);
        $unmatched = $hand->getUnmatched();
        $card = null;

        if ($unmatched) {
            $card = $unmatched[array_rand($unmatched)];
            foreach ($unmatched as $unmatchedCard) {
                if ($card->getValue() < $unmatchedCard->getValue()) {
                    $card = $unmatchedCard;
                }
            }
            $hand->drawByPattern($card->getSuit(), $card->getValue());
            $card->reveal();
            $this->discard->add($card);
        }

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

    public function addToPlayersMeld(GinRummyHand $playerHand): bool
    {
        $opponentHand = $this->opponent->getHand();
        $unmatched = $opponentHand->getUnmatched();
        $changesMadeFlag = true;
        $meldedOnce = false;

        while ($changesMadeFlag) {
            $changesMadeFlag = false;
            foreach ($unmatched as $card) {
                $value = $card->getValue();
                $suit = $card->getSuit();
                $success = $this->scoring->addToOthersMeld($suit, $value, $opponentHand, $playerHand);

                if ($success) {
                    $changesMadeFlag = true;
                    $meldedOnce = true;
                }
            }
        }

        return $meldedOnce;
    }

    public function mainStep (Round $round): string
    {
        $card = $this->drawDeckOrDrawDiscard();
        $flash = "Drar kort från kortleken.";
        if ($card) {
            $flash = "Väljer {$card->getValue()} of {$card->getSuit()} från slänghögen.";
        }

        $this->discard();
        $flash .= " Slänger kort.";

        $knock = $this->knockOrPass();
        if ($knock) {
            $opponentHand = $this->opponent->getHand();
            $this->scoring->meld($opponentHand);
            $opponentHand->revealAll();
            $flash .= " Knackar. Välj kort att lägga till motståndarens serier.";
            $round->setStep(3);
            return $flash;
        }

        $round->setStep(0);
        $this->opponent->getHand()->resetMelds();

        return $flash;
    }

    public function knockStep (Round $round, GinRummyHand $playerHand): string
    {

        // lägga kort på knack
        $this->addToPlayersMeld($playerHand);
        $flash = "Försöker lägga kort till dina serier.";

        $round->setStep(7);

        return $flash;
    }

    public function topCardChoiceStep(Round $round): string
    {
        $currentStep = $round->getStep();
        $card = $this->drawOrPass();
        $flash = 'Passar. Du måste nu välja kortet i slänghögen eller passa.';
        $nextStep = 5;

        if ($currentStep === 5) {
            $flash = 'Passar. Du måste nu välja översta kortet i leken.';
            $nextStep = 6;
        }

        if ($card) {
            $this->discard();
            $flash = "Väljer {$card->getValue()} of {$card->getSuit()} från slänghögen. Slänger.";
            $nextStep = 0;
        }

        $round->setStep($nextStep);
        $this->opponent->getHand()->resetMelds();

        return $flash;
    }

    // public function topCardForcedStep()
    // {

    // }
}
