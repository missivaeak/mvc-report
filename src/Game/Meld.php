<?php

namespace App\Game;

use App\Game\CardCollectionAbstract;
use App\Errors\InvalidMeldTypeException;
use Exception;

class Meld extends CardCollectionAbstract
{
    private bool $isSet;
    private bool $isRun;

    public function __construct(string $type)
    {
        parent::__construct();
        $this->isRun = false;
        $this->isSet = false;

        if ($type === "run") {
            $this->isRun = true;
        } elseif ($type === "set") {
            $this->isSet = true;
        } else {
            throw new InvalidMeldTypeException();
        }
    }

    public function isRun(): bool
    {
        return $this->isRun;
    }

    public function isSet(): bool
    {
        return $this->isSet;
    }

    public function getValue(): ?int
    {
        if ($this->isRun() === true || count($this->getCards()) === 0) {
            return null;
        }

        $firstCard = $this->cards[0];

        return $firstCard->getValue();
        // return 2;
    }

    public function getSuit(): ?string
    {
        if ($this->isSet() === true || count($this->getCards()) === 0) {
            return null;
        }

        $firstCard = $this->cards[0];

        return $firstCard->getSuit();
    }
}
