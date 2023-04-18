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
        foreach ($hand->getUnmatched() as $card) {
            $value = $card->getValue();
            $suit = $card->getSuit();
            $nextCard = new StandardPlayingCard($suit, $value + 1);
            $nextNextCard = new StandardPlayingCard($suit, $value + 2);


            if (in_array(strval($nextCard), $hand->getUnmatched())
                && in_array(strval($nextNextCard), $hand->getUnmatched())) {

                // create new meld
                $meldIndex = $hand->addMeld(new Meld("run"));

                // arrange the run
                $run = [$card, $nextCard, $nextNextCard];

                // add each card to the meld
                foreach ($run as $cardInRun) {
                    $index = -1;
                    foreach ($hand->getUnmatched() as $key => $unmatchedCard) {
                        if (($unmatchedCard->getValue() === $cardInRun->getValue())
                        && ($unmatchedCard->getSuit() === $cardInRun->getSuit())) {
                            $index = $key;
                            break;
                        }
                    }
                    // $index = array_search($cardInRun, $hand->getUnmatched());
                    if ($index > 0) {
                        $hand->addToMeld($index, $meldIndex);
                    }
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

    public function fitUnmatchedCards(GinRummyHand $hand): bool
    {
        $unmatched = $hand->getUnmatched();
        $melds = $hand->getMelds();
        $success = false;

        foreach ($melds as $meldIndex => $meld) {
            if ($meld->isRun() === true) {
                $suit = strval($meld->getSuit());

                $changesMadeFlag = true;

                while ($changesMadeFlag) {
                    $changesMadeFlag = false;
                    for ($i = 0; $i < count($unmatched); $i++) {
                        $values = array_map(function ($card) {
                            return $card->getValue();
                        }, $meld->getCards());
                        sort($values);

                        $lowerValue = $values[0] - 1;
                        $lowerResult = $this->fitUnmatchedCard($hand, $meldIndex, $suit, $lowerValue);

                        $higherValue = $values[array_key_last($values)] + 1;
                        $higherResult = $this->fitUnmatchedCard($hand, $meldIndex, $suit, $higherValue);

                        // throw new Exception($highest);
                        if ($lowerResult || $higherResult) {
                            $success = true;
                            $changesMadeFlag = true;
                        }
                    }
                }
            } elseif ($meld->isSet() === true && count($meld->getCards()) === 3) {
                $value = $meld->getValue() ?? 0;
                $suits = array_map(function ($card) {
                    return $card->getSuit();
                }, $meld->getCards());
                $remainingSuit = strval(array_values(array_diff($this->suits, $suits))[0]);
                $result = $this->fitUnmatchedCard($hand, $meldIndex, $remainingSuit, $value);
                if ($result) {
                    $success = true;
                }
            }
        }

        return $success;
    }

    private function fitUnmatchedCard(GinRummyHand $hand, int $meldIndex, string $suit, int $value): bool
    {
        $cardIndex = -1;
        foreach ($hand->getUnmatched() as $key => $card) {
            if ($card->getSuit() === $suit && $card->getValue() === $value) {
                $cardIndex = $key;
                break;
            }
        }
        if ($cardIndex > -1) {
            $hand->addToMeld($cardIndex, $meldIndex);
            return true;
        }
        return false;
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

        // throw new Exception($score);

        return $score;
    }

    public function addToOthersMeld(
        string $suit,
        int $value,
        GinRummyHand $thisHand,
        GinRummyHand $otherHand
    ): bool {
        $card = $thisHand->drawByPattern($suit, $value);
        // $thisHand->add($card);
        $melds = $otherHand->getMelds();
        // throw new Exception(count($melds));
        foreach ($melds as $meld) {
            if ($meld->isRun() === true && $meld->getSuit() === $suit) {
                // throw new Exception($meld->getSuit());
                $values = array_map(function ($cardInMeld) {
                    return $cardInMeld->getValue();
                }, $meld->getCards());

                $lowerValue = $values[0] - 1;
                $higherValue = $values[array_key_last($values)] + 1;

                if ($card && ($value === $lowerValue || $value === $higherValue)) {
                    $otherHand->add($card);
                    $meld->add($card);
                    $card->reveal();
                    return true;
                }
            } elseif ($meld->isSet() === true && $meld->getValue() === $value) {
                $suits = array_map(function ($cardInMeld) {
                    return $cardInMeld->getSuit();
                }, $meld->getCards());

                $remainingSuit = array_values(array_diff($this->suits, $suits))[0];

                if ($card && $suit === $remainingSuit) {
                    $otherHand->add($card);
                    $meld->add($card);
                    $card->reveal();
                    return true;
                }
            }
        }

        if ($card) {
            $thisHand->add($card);
        }
        $this->meld($otherHand);

        return false;
    }
}
