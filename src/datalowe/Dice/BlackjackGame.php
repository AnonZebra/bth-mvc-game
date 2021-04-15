<?php

declare(strict_types=1);

namespace dtlw\Dice;

use dtlw\Dice\BlackjackPlayer as Player;
use dtlw\Dice\InvalidNumberOfDice as InvalidNumberOfDice;

/**
* Implements a game where two players play 'blackjack with dice', where
* each player keeps rolling the dice until they are satisfied or have
* reached/surpassed 21. Whoever has a total score closest to, while not above,
* 21 wins.
*/
class BlackjackGame
{
    /**
    * @var Player $humanPlayer Human player.
    * @var Player $autoPlayer Automated player.
    */
    private $humanPlayer;
    private $autoPlayer;
    private $roundEnded = false;

    /**
    * @param int $numDice The number of dice to be rolled for each player
    * roll - must be 1 or 2.
    */
    public function __construct(int $numDice = 1)
    {
        if ($numDice < 1 || $numDice > 2) {
            throw new InvalidNumberOfDice("Inkorrekt antal tärningar");
        }
        $this->humanPlayer = new Player($numDice);
        $this->autoPlayer = new Player($numDice);
    }

    /**
    * @return int The player's post-roll updated score.
    */
    public function rollHumanDice()
    {
        $player = $this->humanPlayer;
        $player->roll();
        return $player->getScore();
    }

    /**
    * @return int[] The players' current scores.
    */
    public function getScores()
    {
        return [
            "human" => $this->humanPlayer->getScore(),
            "computer" => $this->autoPlayer->getScore()
        ];
    }

    /**
    * @return int[] The players' numbers of won rounds.
    */
    public function getWonRounds()
    {
        return [
            "human" => $this->humanPlayer->getWonRounds(),
            "computer" => $this->autoPlayer->getWonRounds()
        ];
    }

    /**
    * Make the automated player play until finishing.
    */
    public function autoPlay()
    {
        $this->autoPlayer->autoPlay($this->humanPlayer->getScore());
    }

    /**
    * Check who won the round based on current scores and, unless
    * they are all equal, increase the winner's number of won rounds.
    * Reset players' scores.
    */
    public function endRound()
    {
        $scores = $this->getScores();
        if ($scores["human"] > $scores["computer"] && $scores["human"] < 22) {
            $this->humanPlayer->bumpWonRounds();
        } elseif ($scores["human"] < 22 && $scores["computer"] > 21) {
            $this->humanPlayer->bumpWonRounds();
        } else {
            $this->autoPlayer->bumpWonRounds();
        }
        $this->roundEnded = true;
    }

    /**
    * @return string Name of winning player.
    */
    public function getWinnerName(): string
    {
        $scores = $this->getScores();
        if ($scores["human"] > $scores["computer"] && $scores["human"] < 22) {
            return "Du";
        } elseif ($scores["human"] < 22 && $scores["computer"] > 21) {
            return "Du";
        } else {
            return "Datorn";
        }
    }

    /**
    * Reset player scores to start a new round.
    */
    public function newRound()
    {
        $this->humanPlayer->resetScore();
        $this->autoPlayer->resetScore();
        $this->roundEnded = false;
    }

    /**
    * Check if round has ended.
    * @return bool
    */
    public function roundHasEnded()
    {
        return $this->roundEnded;
    }
}
