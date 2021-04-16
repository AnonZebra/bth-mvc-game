<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\DiceGamePlayer;
use dtlw\Dice\DiceHand;
use dtlw\Dice\YahtzeeScoreSheet;
use dtlw\Dice\FullSheetException;

/**
* A player in a dice game, see `BlackjackGame` class.
* @author Lowe Wilsson <datalowe@posteo.de>
* @link www.datalowe.com
*/
class YahtzeePlayer extends DiceGamePlayer
{
    /**
    * @var YahtzeeScoreSheet $scoresheet This player's sheet of yahtzee scores.
    */
    private YahtzeeScoreSheet $scoresheet;
    private int $numConsecutiveRolls = 0;

    public function __construct()
    {
        $dieFactory = new DieFactory('plain');
        parent::__construct(5, $dieFactory);
        $this->scoresheet = new YahtzeeScoreSheet();
    }

    /**
    * Rolls this player's hand of dice.
    * @param int[] $skipIndices (optional) Specification
    * of which of the hand's dice are to be left as they are, ie
    * are _not_ to be rolled.
    */
    public function roll($skipIndices = []): void
    {
        $this->hand->roll($skipIndices);
        $this->numConsecutiveRolls += 1;
    }

    /**
    * Stores the results of player's current hand to score sheet.
    */
    public function registerRoll()
    {
        $categoryName = $this->scoresheet->getScoreLessCategoryName();
        $categoryVal = intval($categoryName);
        $matchingVals = array_filter(
            $this->hand->getLastRoll(),
            fn($val) => $val == $categoryVal
        );
        if (count($matchingVals) == 0) {
            $categoryTotal = 0;
        } else {
            $categoryTotal = array_reduce(
                $matchingVals,
                fn($val1, $val2) => $val1 + $val2
            );
        }

        $this->scoresheet->setCategoryScore($categoryName, $categoryTotal);
        $this->numConsecutiveRolls = 0;
    }

    /**
    * @return int The players' current total score.
    */
    public function getScore(): int
    {
        return $this->scoresheet->getTotalScore();
    }

    /**
    * Resets this player's scoresheet.
    */
    public function resetScore(): void
    {
        $this->scoresheet->reset();
    }

    public function getNumConsecutiveRolls(): int
    {
        return $this->numConsecutiveRolls;
    }

    /**
    * @return int[] The values of the this player's last roll/
    * current hand.
    */
    public function getCurrentDieValues(): array
    {
        return $this->hand->getDievalues();
    }

    public function finished(): bool
    {
        return $this->scoresheet->isFilledOut();
    }

    /**
    * @return int The die value that the player is to try to get as many
    * of as possible right now (or 0 if no values are left to fill in).
    */
    public function getGoalValue(): int
    {
        try {
            return intval($this->scoresheet->getScoreLessCategoryName());
        } catch (FullSheetException $e) {
            return 0;
        }
    }
}
