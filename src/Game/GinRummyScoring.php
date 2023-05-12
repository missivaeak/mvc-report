<?php

namespace App\Game;

use App\Game\Meld;
use App\Game\GinRummyHand;
use App\Game\StandardPlayingCard;
use Exception;

// use App\Game\StandardPlayingCardsTrait

class GinRummyScoring
{
    use StandardPlayingCardsTrait;

    private function findRuns(GinRummyHand $hand): void
    {
        // for each unmatched card see if that that card's
        // two higher value cards exist among unmatched cards
        $unmatched = $hand->getUnmatched();
        foreach ($unmatched as $card) {
            $value = $card->getValue();
            $suit = $card->getSuit();
            $nextCard = $hand->getByPattern($suit, $value + 1);
            $nextNextCard = $hand->getByPattern($suit, $value + 2);


            if ($nextCard && $nextNextCard) {
                // create new meld
                $meld = new Meld("run");
                $hand->addMeld($meld);

                // arrange the run
                $run = [$card, $nextCard, $nextNextCard];

                // add each card to the meld
                foreach ($run as $cardInRun) {
                    // $suit = $dummyCard->getSuit();
                    // $value = $dummyCard->getValue();
                    // $cardInRun = $hand->getByPattern($suit, $value);
                    $meld->add($cardInRun);
                    // $index = intval(array_search($cardInRun, $hand->getUnmatched()));
                    // if ($index > 0) {
                    //     $hand->addToMeld($index, $meldIndex);
                    // }
                }
            }
        }
    }

    private function findSets(GinRummyHand $hand): void
    {
        // put all cards in an associative array
        // with their value as key
        $sets = [];
        for ($i = 1; $i <= $this->values; $i++) {
            $sets[$i] = array_filter(
                $hand->getUnmatched(),
                function ($card) use ($i) {
                    return $card->getValue() === $i;
                }
            );
        }

        // for each potential set check if it contains
        // three or more cards
        foreach ($sets as $set) {
            if (count($set) >= 3) {
                $meldIndex = $hand->addMeld(new Meld("set"));
                $i = 0;

                // add only three cards to the meld at this point
                foreach ($set as $cardInSet) {
                    if ($i == 3) {
                        break;
                    }
                    $index = array_search($cardInSet, $hand->getUnmatched());
                    if (gettype($index) === "integer") {
                        $hand->addToMeld($index, $meldIndex);
                    }
                    $i++;
                }
            }
        }
    }

    private function fitUnmatchedCards(GinRummyHand $hand): bool
    {
        $unmatched = $hand->getUnmatched();
        $melds = $hand->getMelds();
        $success = false;

        foreach ($unmatched as $card) {
            foreach ($melds as $meld) {
                $value = $card->getValue();
                $suit = $card->getSuit();
                $this->addToOthersMeld($suit, $value, $hand, $hand);
            }
        }

        return $success;
    }

    private function meldRunsFirst(GinRummyHand $hand): int
    {
        $hand->resetMelds();
        $this->findRuns($hand);
        $this->findSets($hand);
        $this->fitUnmatchedCards($hand);
        return $this->handScore($hand);
    }

    private function meldSetsFirst(GinRummyHand $hand): int
    {
        $hand->resetMelds();
        $this->findSets($hand);
        $this->findRuns($hand);
        $this->fitUnmatchedCards($hand);
        return $this->handScore($hand);
    }

    public function meld(GinRummyHand $hand): int
    {
        $runsFirst = $this->meldRunsFirst($hand);
        $setsFirst = $this->meldSetsFirst($hand);
        if ($runsFirst >= $setsFirst) {
            return $setsFirst;
        }
        return $this->meldRunsFirst($hand);
    }

    public function handScore(GinRummyHand $hand): int
    {
        $unmatched = $hand->getUnmatched();
        $score = 0;
        foreach ($unmatched as $card) {
            $value = $card->getValue();
            if ($value > 10) {
                $value = 10;
            }
            $score += $value;
        }

        return $score;
    }

    public function addToOthersMeld(
        string $suit,
        int $value,
        GinRummyHand $thisHand,
        GinRummyHand $otherHand
    ): bool {
        $card = $thisHand->drawByPattern($suit, $value);
        $melds = $otherHand->getMelds();
        $shouldMeldFlag = false;

        if (!$card) {
            return false;
        }

        foreach ($melds as $meld) {
            if (
                $meld->isRun() === true
                && $meld->getSuit() === $suit
            ) {
                $shouldMeldFlag = $this->shouldCardMeldWithRun($card, $meld);
            } elseif (
                $meld->isSet() === true
                && $meld->getValue() === $value
            ) {
                $shouldMeldFlag = $this->shouldCardMeldWithSet($card, $meld);
            }

            if ($shouldMeldFlag) {
                $otherHand->add($card);
                $meld->add($card);

                return true;
            }
        }

        $thisHand->add($card);

        return false;
    }

    private function shouldCardMeldWithRun(
        CardInterface $card,
        Meld $meld
    ): bool {
        $value = $card->getValue();
        // throw new Exception($meld->getSuit());
        $values = array_map(function ($cardInMeld) {
            return $cardInMeld->getValue();
        }, $meld->getCards());

        $lowerValue = $values[0] - 1;
        $higherValue = $values[array_key_last($values)] + 1;

        if ($value === $lowerValue || $value === $higherValue) {
            return true;
        }

        return false;
    }

    private function shouldCardMeldWithSet(
        CardInterface $card,
        Meld $meld
    ): bool {
        $suit = $card->getSuit();
        $suits = array_map(function ($cardInMeld) {
            return $cardInMeld->getSuit();
        }, $meld->getCards());

        $remainingSuit = array_values(array_diff($this->suits, $suits))[0];

        if ($suit === $remainingSuit) {
            return true;
        }

        return false;
    }

    public function checkScoreDiff(
        Player $player,
        Player $opponent,
        Game $game
    ): string
    {
        //beräknar poäng
        $playerScore = $this->handScore($player->getHand());
        $opponentScore = $this->handScore($opponent->getHand());
        $difference = $opponentScore - $playerScore;
        $points = $game->score($player, $opponent, $difference);
        $scoreFlash = " Lika. Inga poäng delades ut.";
        if ($playerScore === 0) {
            $points = $opponentScore + $game->getGinBonus();
            $scoreFlash = " Du har gin och får $points poäng.";
        } else {
            if ($points > 0) {
                $scoreFlash = " Du vinner och får $points poäng.";
            } elseif ($points < 0) {
                $points = abs($points);
                $scoreFlash = " Motståndaren vinner och får $points poäng.";
            }
        }
        return $scoreFlash;
    }
}
