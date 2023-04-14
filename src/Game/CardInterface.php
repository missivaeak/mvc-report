<?php

namespace App\Game;

interface CardInterface
{
    public function hide(): void;

    public function reveal(): void;

    public function getFace(): string;

    public function getSuit(): string;

    public function getValue(): int;

    public function __toString(): string;
}
