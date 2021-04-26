<?php

declare(strict_types=1);

namespace dtlw\Dice;

/**
* Class for representing hands of dice.
*/
class DiceHand
{
    /**
    * @var Dice[] $dice Array of dice.
    */
    private $dice = array();

    public function __construct(int $numDice, DieFactory $dieFactory)
    {
        for ($i = 0; $i < $numDice; $i++) {
            array_unshift($this->dice, $dieFactory->make());
        }
    }

    /**
    * Rolls the hand's dice, updating their values.
    * @param int[] $skipIndices (optional) Specification
    * of which of the hand's dice are to be left as they are, ie
    * are _not_ to be rolled.
    */
    public function roll($skipIndices = []): void
    {
        for ($i = 0; $i < count($this->dice); $i++) {
            if (in_array($i, $skipIndices)) {
                continue;
            }
            $this->dice[$i]->roll();
        }
    }

    /**
    * Returns all of the hand's dice's current values.
    * @return int[] Current dice values.
    */
    public function getLastRoll(): array
    {
        try {
            return array_map(fn($die) => $die->getLastRoll(), $this->dice);
        } catch (HaventRolledYetException $e) {
            throw new HaventRolledYetException("Hand must first be rolled.");
        }
    }

    /**
    * Returns relative paths locating the hand's dice's corresponding
    * die face images, to be used with 'src' attribute in HTML.
    * @return string[] Relative
    */
    public function getFaceImgs()
    {
        return array_map(fn($die) => $die->getFaceImg(), $this->dice);
    }

    /**
    * Returns the sum of the hand's dice's values.
    * @return int Sum of die values.
    */
    public function getLastRollTotal()
    {
        return array_reduce(
            $this->getLastRoll(),
            fn($rollVal1, $rollVal2) => $rollVal1 + $rollVal2
        );
    }

    /**
    * @return int[] All of the hand's current/last roll die values.
    */
    public function getDieValues(): array
    {
        try {
            $dVals = array_map(
                fn($die) => $die->getLastRoll(),
                $this->dice
            );
        } catch (HaventRolledYetException $e) {
            $dVals = [];
        }
        return $dVals;
    }
}
