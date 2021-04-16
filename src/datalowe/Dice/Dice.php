<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\HaventRolledYetException;

/**
* Class for representing dice.
*/
class Dice implements Rollable
{
    // number of sides/faces of die
    private $numSides = 0;
    // result of last roll
    private $lastRoll;

    /**
    * Constructor
    * @param int $numSides - Number of die faces.
    */
    public function __construct(int $numSides = 6)
    {
        $this->numSides = $numSides;
    }

    /**
    * Rolls the die and returns the result.
    * @return int Roll result.
    */
    public function roll(): void
    {
        $this->lastRoll = rand(1, $this->numSides);
    }

    /**
    * Fetches and returns last roll result, as a one-element array.
    * @return int[] Roll result.
    */
    public function getLastRoll(): array
    {
        return [$this->lastRoll];
    }

    /**
    * Fetches and returns last roll result, as an integer.
    * @return int Roll result.
    */
    public function getLastRollTotal(): int
    {
        if (is_null($this->lastRoll)) {
            throw new HaventRolledYetException();
        }
        return $this->lastRoll;
    }

    /**
    * Updates the number of die faces.
    * @param int $numSides - Number of die faces to update to.
    */
    public function setNumSides(int $numSides)
    {
        $this->numSides = $numSides;
    }
}
