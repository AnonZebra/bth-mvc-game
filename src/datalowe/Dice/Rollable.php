<?php

declare(strict_types=1);

namespace dtlw\Dice;

/**
* An interface for representing something that can be rolled, in the sense
* of rolling a die.
*/
interface Rollable
{
    /**
    * Should return an array of integers,
    * _even_ if it's just one integer,
    * to make the interface more consistent.
    */
    public function getLastRoll(): array;

    public function getLastRollTotal(): int;

    public function roll(): void;
}
