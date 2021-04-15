<?php

declare(strict_types=1);

namespace dtlw\Dice;

use function Mos\Functions\{
    getBaseUrl
};

/**
* Class for representing dice.
*/
class GraphicalDice extends Dice
{
    // number of sides/faces of die
    // protected $numSides = 6;
    // result of last roll

    /**
    * Constructor
    */
    public function __construct()
    {
        parent::__construct(6);
    }
    /**
    * Updates the number of die faces.
    */
    public function setNumSides(int $numSides)
    {
    }

    /**
    * Gets a string for an image url.
    */
    public function getFaceImg(): string
    {
        return (
            getBaseUrl() . "/img/die/die{$this->getLastRollTotal()}.svg"
        );
    }
}
