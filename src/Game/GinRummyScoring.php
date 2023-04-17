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

    private function findRuns(GinRummyHand $hand)
    {
        // for each unmatched card see if that that card's
        // two higher value cards exist among unmatched cards
        foreach ($hand->getUnmatched() as $card) {
            $value = $card->getValue();
            $suit = $card->getSuit();
            $nextCard = new StandardPlayingCard($suit, $value + 1);
            $nextNextCard = new StandardPlayingCard($suit, $value + 2);

            if (
                in_array($nextCard, $hand->getUnmatched())
                && in_array($nextNextCard, $hand->getUnmatched())
            ) {
                // create new meld
                $meldIndex = $hand->addMeld(new Meld("run"));

                // arrange the run
                $run = [$card, $nextCard, $nextNextCard];

                // add each card to the meld
                foreach ($run as $cardInRun) {
                    $index = array_search($cardInRun, $hand->getUnmatched());
                    $hand->addToMeld($index, $meldIndex);
                }
            }
        }
    }

    private function findSets(GinRummyHand $hand)
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
                    $hand->addToMeld($index, $meldIndex);
                    $i++;
                }
            }
        }
    }

    public function fitUnmatchedCards(GinRummyHand $hand): bool
    {
        // BEGIN fitUnmatchedCards
        //     // try to add remaining unmatched cards to melds
        //     FOR each unmatched card
        //         FOR each run
        //             IF can be added to the run
        //                 ADD to run
        //     FOR each unmatched card
        //         FOR each set
        //             IF can be added to the set
        //                 ADD to set
        // END
        $unmatched = $hand->getUnmatched();
        $melds = $hand->getMelds();
        $success = false;

        foreach ($melds as $meldIndex => $meld) {
            if ($meld->isRun() === true) {
                $suit = $meld->getSuit();

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
                $value = $meld->getValue();
                $suits = array_map(function ($card) {
                    return $card->getSuit();
                }, $meld->getCards());
                $remainingSuit = array_values(array_diff($this->suits, $suits))[0];
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

    public function meld(GinRummyHand $hand)
    {
        $this->findRuns($hand);
        $this->findSets($hand);
        $this->fitUnmatchedCards($hand);
    }

    public function handScore(GinRummyHand $hand)
    {
        $unmatched = $hand->getUnmatched();
        $score = 0;
        foreach ($unmatched as $card) {
            $value = $card->getValue();
            if ($value > 10) { $value = 10; }
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
    ): bool
    {
        $card = $thisHand->drawByPattern($suit, $value);
        // $thisHand->add($card);
        $melds = $otherHand->getMelds();
        // throw new Exception(count($melds));
        foreach ($melds as $meld) {
            if ($meld->isRun() === true && $meld->getSuit() === $suit) {
                // throw new Exception($meld->getSuit());
                $values = array_map(function ($card) {
                    return $card->getValue();
                }, $meld->getCards());

                $lowerValue = $values[0] - 1;
                $higherValue = $values[array_key_last($values)] + 1;

                if ($value === $lowerValue || $value === $higherValue) {
                    $meld->add($card);
                    $card->reveal();
                    return true;
                }
            } elseif ($meld->isSet() === true && $meld->getValue() === $value) {
                $suits = array_map(function ($card) {
                    return $card->getSuit();
                }, $meld->getCards());

                $remainingSuit = array_values(array_diff($this->suits, $suits))[0];

                if ($suit === $remainingSuit) {
                    $meld->add($card);
                    $card->reveal();
                    return true;
                }
            }
        }

        $thisHand->add($card);
        $this->meld($otherHand);

        return false;
    }
}
