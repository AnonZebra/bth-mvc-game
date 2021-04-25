<?php

declare(strict_types=1);

namespace dtlw\Dice;

/**
* Class whose instances are to generate different kinds of
* dice, based on specification passed at time of creation.
*/
class DieFactory
{
    private $numSides;
    private string $dieType;
    private static $validTypes = [
        'graphical',
        'plain'
    ];

    /**
    * @param string $dieType A name of the type of die that the
    * factory is to generate. 'graphical' or 'plain'.
    */
    public function __construct(string $dieType, $numSides = 6)
    {
        if (!in_array($dieType, self::$validTypes)) {
            throw new InvalidDieTypeException(
                "The specified die type is not supported"
            );
        }
        $this->dieType = $dieType;
        $this->numSides = $numSides;
    }

    /**
    * Generates and returns a new instance of this factory's
    * die type.
    * @return Dice
    */
    public function make(): Dice
    {
        if ($this->dieType == 'graphical') {
            return new GraphicalDice();
        } elseif ($this->dieType == 'plain') {
            return new Dice($this->numSides);
        }
        throw new InvalidDieTypeException();
    }
}
