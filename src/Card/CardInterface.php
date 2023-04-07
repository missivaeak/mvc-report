<?php

namespace App\Card;

interface CardInterface
{
    public function hide();

    public function reveal();

    public function look(): ?string;

    public function peek(): string;
}
