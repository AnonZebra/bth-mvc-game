<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\DiceGamePlayer;
use dtlw\Dice\DiceHand;
use dtlw\Dice\YahtzeeScoreSheet;

/**
* A player in a dice game, see `BlackjackGame` class.
* @author Lowe Wilsson <datalowe@posteo.de>
* @link www.datalowe.com
*/
class YahtzeePlayer extends DiceGamePlayer
{


    /**
    * @var YahtzeeScoreSheet $scoreSheet This player's sheet of yahtzee scores.
    */
    private YahtzeeScoreSheet $scoresheet;

    /**
    * @param int $numDice The number of dice to be included in the player's
    * hand.
    */
    public function __construct(int $numDice)
    {
        $dieFactory = new DieFactory('plain');
        parent::__construct($numDice, $dieFactory);
        $this->scoresheet = new YahtzeeScoreSheet();
    }

    /**
    * Rolls this player's hand of dice.
    * @param int[] $skipIndices (optional) Specification
    * of which of the hand's dice are to be left as they are, ie
    * are _not_ to be rolled.
    */
    public function roll(($skipIndices = [])): void
    {
        $this->hand->roll($skipIndices);
    }

    /**
    * Stores the results of player's current hand to score sheet.
    */
    public function registerRoll()
    {
        $categoryName = $this->scoresheet->getScoreLessCategoryName();
        $categoryVal = intval($categoryName);
        $categoryTotal = array_reduce(
            array_filter(
                $this->hand->getLastRoll(),
                fn($val) => $val == $categoryVal
            ),
            fn($val1, $val2) => $val1 + $val2
        );
        $this->scoresheet->setCategoryScore($categoryName, $categoryTotal);
    }

    /**
    * Keeps
    */
    public function autoPlay(int $surpassValue)
    {
    }
}
