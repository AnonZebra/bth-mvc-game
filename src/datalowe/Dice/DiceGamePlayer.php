<?php

declare(strict_types=1);

namespace dtlw\Dice;

/**
* Represents players in games of dice.
* @author Lowe Wilsson <datalowe@posteo.de>
* @link www.datalowe.com
*/
abstract class DiceGamePlayer
{
    /**
    * @var DiceHand $hand This player's hand of dice.
    * @var int $score This player's total dice roll score.
    * @var int $wonRounds Number of rounds that this player has won.
    */
    protected $hand;
    protected $score = 0;
    private $wonRounds = 0;

    /**
    * @param int $numDice The number of dice to be included in the player's
    * hand.
    */
    public function __construct(int $numDice, $dieFactory)
    {
        $this->hand = new DiceHand($numDice, $dieFactory);
    }

    /**
    * Roll this player's hand of dice, adding the value sum to the
    * player's score.
    */
    public function roll(): void
    {
    }

    /**
    * @return int Player's current score.
    */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
    * Resets player score.
    */
    public function resetScore(): void
    {
        $this->score = 0;
    }

    /**
    * Increment the number of rounds won by player by one.
    */
    public function bumpWonRounds(): void
    {
        $this->wonRounds++;
    }

    /**
    * @return int
    */
    public function getWonRounds(): int
    {
        return $this->wonRounds;
    }

    // /**
    // * Reset this player's number of won rounds.
    // */
    // public function resetWonRounds(): void
    // {
    //     $this->wonRounds = 0;
    // }
}
