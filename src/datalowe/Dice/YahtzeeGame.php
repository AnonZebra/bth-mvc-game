<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\YahtzeePlayer as Player;

/**
* Implements a game where a single player plays a limited version of Yahtzee,
* where only score categories '1' through '6' are used.
*/
class YahtzeeGame
{
    /**
    * @var Player $humanPlayer Human player.
    * @var Player $activePlayer Currently active player.
    * @var bool $roundEnded true if round has ended, otherwise false.
    */
    private $humanPlayer;
    private $activePlayer;
    private $roundEnded = false;
    private const MAX_NUM_ROLLS = 3;

    public function __construct()
    {
        $this->humanPlayer = new Player();
        $this->activePlayer = $this->humanPlayer;
    }

    // TODO add functionality for additional players
    /**
    * Starts the round, setting the first player as the active player.
    */
    public function startRound(): void
    {
        $this->activePlayer = $this->humanPlayer;
    }

    // TODO add functionality for additional players, by making
    // this function update the active player at end, or
    // call a `nextTurn` method
    /**
    * Makes the currently active player object register its score and
    * reset its number of consecutive rolls.
    */
    public function registerActiveRoll(): void
    {
        $this->activePlayer->registerRoll();
        if ($this->activePlayer->finished()) {
            $this->roundEnded = true;
        }
    }

    /**
    * Rolls active player's hand of dice.
    * @param int[] $skipIndices (optional) Specification
    * of which of the player's hand's dice are to be left as they are, ie
    * are _not_ to be rolled.
    */
    public function rollDice($skipIndices = []): void
    {
        $numRolled = $this->activePlayer->getNumConsecutiveRolls();
        if ($numRolled >= self::MAX_NUM_ROLLS) {
            throw new TooManyRollsException();
        }
        $this->activePlayer->roll($skipIndices);
    }

    /**
    * @return int[] The players' current scores.
    */
    public function getScores(): array
    {
        return [
            "human" => $this->humanPlayer->getScore()
        ];
    }

    public function endRound(): void
    {
        $this->roundEnded = true;
    }

    /**
    * Reset player scores to start a new round.
    */
    public function newRound(): void
    {
        $this->humanPlayer->resetScore();
        $this->roundEnded = false;
    }

    /**
    * Check if round has ended.
    * @return bool
    */
    public function roundHasEnded(): bool
    {
        return $this->roundEnded;
    }

    public function getGoalValue(): int
    {
        return $this->activePlayer->getGoalValue();
    }

    /**
    * @return int The number of rolls that the currently active player
    * has left.
    */
    public function getNumRollsLeft(): int
    {
        $numConsecutive = $this->activePlayer->getNumConsecutiveRolls();
        return self::MAX_NUM_ROLLS - $numConsecutive;
    }

    public function getCurrentRollNumber(): int
    {
        return $this->activePlayer->getNumConsecutiveRolls() + 1;
    }

    /**
    * @return int[] The values of the currently active player's last roll/
    * current hand.
    */
    public function getCurrentDieValues(): array
    {
        try {
            return $this->activePlayer->getCurrentDieValues();
        } catch (HaventRolledYetException $e) {
            throw $e;
        }
    }
}
