<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\DiceGamePlayer;
use dtlw\Dice\DiceHand;

/**
* A player in a dice game, see `BlackjackGame` class.
* @author Lowe Wilsson <datalowe@posteo.de>
* @link www.datalowe.com
*/
class BlackjackPlayer extends DiceGamePlayer
{
    /**
    * @var DiceHand $hand This player's hand of dice.
    * @var int $score This player's total dice roll score.
    * @var int $wonRounds Number of rounds that this player has won.
    */
    /**
    * @param int $numDice The number of dice to be included in the player's
    * hand.
    */
    public function __construct(int $numDice)
    {
        $dieFactory = new DieFactory('plain');
        parent::__construct($numDice, $dieFactory);
    }

    /**
    * Roll this player's hand of dice, adding the value sum to the
    * player's score.
    */
    public function roll(): void
    {
        $this->hand->roll();
        $this->incrementScore($this->hand->getLastRollTotal());
    }

    /**
    * Increment player score
    * @param int $incVal Value to increment score by
    */
    protected function incrementScore(int $incVal): void
    {
        $this->score += $incVal;
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
}
