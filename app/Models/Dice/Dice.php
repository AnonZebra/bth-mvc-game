<?php

declare(strict_types=1);

namespace App\Models\Dice;

/**
* Class for representing dice.
*/
class Dice implements Rollable
{
    // number of sides/faces of die
    private $numSides = 0;
    // result of last roll
    private $lastRoll = null;

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
    */
    public function roll(): void
    {
        $this->lastRoll = rand(1, $this->numSides);
    }

    /**
    * Fetches and returns last roll result.
    * @return int Roll result.
    */
    public function getLastRoll(): int
    {
        if (is_null($this->lastRoll)) {
            throw new HaventRolledYetException("The die must first be rolled.");
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
