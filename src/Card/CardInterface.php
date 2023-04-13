<?php

namespace App\Card;

interface CardInterface
{
    public function hide(): void;

    public function reveal(): void;

    public function look(): ?string;

    public function peek(): string;
}
