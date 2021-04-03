<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\GraphicalDice as SingleDie;

/**
* Class for representing hands of dice.
*/
class DiceHand
{
    /**
    * @var SingleDie[] $dice Array of dice.
    */
    private $dice = array();

    public function __construct(int $numDice)
    {
        for ($i = 0; $i < $numDice; $i++) {
            array_unshift($this->dice, new SingleDie());
        }
    }

    /**
    * Rolls the hand's dice, updating their values.
    */
    public function roll()
    {
        $makeRoll = function (SingleDie $die): void {
            $die->roll();
        };

        array_map($makeRoll, $this->dice);
    }

    /**
    * Returns all of the hand's dice's current values.
    * @return int[] Current dice values.
    */
    public function getRolls(): array
    {
        $getRoll = function (SingleDie $die): int {
            return $die->getLastRoll();
        };
        return array_map($getRoll, $this->dice);
    }

    /**
    * Returns relative paths locating the hand's dice's corresponding
    * die face images, to be used with 'src' attribute in HTML.
    * @return string[] Relative
    */
    public function getFaceImgs()
    {
        $getFaceImg = function (SingleDie $die): string {
            return $die->getFaceImg();
        };
        return array_map($getFaceImg, $this->dice);
    }

    /**
    * Returns the sum of the hand's dice's values.
    * @return int Sum of die values.
    */
    public function getRollTotal()
    {
        $sumInts = function ($rollVal1, $rollVal2): int {
            return $rollVal1 + $rollVal2;
        };

        return array_reduce($this->getRolls(), $sumInts);
    }
}
