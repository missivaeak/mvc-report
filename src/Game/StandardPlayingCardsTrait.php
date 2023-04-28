<?php

namespace App\Game;

trait StandardPlayingCardsTrait
{
    /**
     * @var int
     */
    private int $values = 13;

    /**
     * @var array<string>
     */
    private array $suits = [
        "hearts",
        "diamonds",
        "clubs",
        "spades"
    ];

    /**
     * @return array<array{suit: string,value: int<1,max>}>
     */
    public function getAllValidCardValues(): array
    {
        $validCardValues = [];

        foreach ($this->suits as $suit) {
            for ($value = 1; $value <= $this->values; $value++) {
                $validCardValues[] = [
                    "suit" => strval($suit),
                    "value" => intval($value)
                ];
            }
        }

        return $validCardValues;
    }
}
