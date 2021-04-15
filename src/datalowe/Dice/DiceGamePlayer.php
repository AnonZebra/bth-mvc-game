<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\DiceHand as DHand;

/**
* A player in a dice game, see `DiceGame` class.
* @author Lowe Wilsson <datalowe@posteo.de>
* @link www.datalowe.com
*/
class DiceGamePlayer
{
    /**
    * @var DiceHand $hand This player's hand of dice.
    * @var int $score This player's total dice roll score.
    * @var int $wonRounds Number of rounds that this player has won.
    */
    private $hand;
    private $score = 0;
    private $wonRounds = 0;

    /**
    * @param int $numDice The number of dice to be included in the player's
    * hand.
    */
    public function __construct(int $numDice)
    {
        $this->hand = new DHand($numDice);
    }

    /**
    * Roll this player's hand of dice, adding the value sum to the
    * player's score.
    */
    public function roll()
    {
        $this->hand->roll();
        $this->score += $this->hand->getLastRollTotal();
    }

    /**
    * @return int Player's current score.
    */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
    * Keeps rolling this player's dice until this player's score has surpassed
    * the passed value and/or surpassed 21.
    * @param int $surpassValue
    */
    public function autoPlay(int $surpassValue)
    {
        if ($surpassValue > 21) {
            return;
        }
        while ($this->score <= $surpassValue && $this->score < 21) {
            $this->roll();
        }
    }

    /**
    * Resets player score.
    */
    public function resetScore()
    {
        $this->score = 0;
    }

    /**
    * Increment the number of rounds won by player by one.
    */
    public function bumpWonRounds()
    {
        $this->wonRounds++;
    }

    /**
    * @return int
    */
    public function getWonRounds()
    {
        return $this->wonRounds;
    }
}
