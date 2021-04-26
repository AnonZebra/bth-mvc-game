<?php

namespace dtlw\Dice;

use PHPUnit\Framework\TestCase;

class YahtzeeGameTest extends TestCase
{
    /**
    * @var YahtzeeGame $player A Yahtzee game with one player with 5 dice.
    */
    private $game;

    public function setUp(): void
    {
        $this->game = new YahtzeeGame();
    }

    public function tearDown(): void
    {
        // reset rand return value
        rand(-2, 0);
    }

    /**
    * rolling dice updates score
    */
    public function testRollDice(): void
    {
        //set rand return value
        rand(-1, 6);
        $this->game->startRound();
        $this->game->rollDice();
        $this->game->registerActiveRoll();
        $this->assertSame($this->game->getScores()["human"], 6 * 5);
    }

    /**
    * rolling dice too many times produces exception
    */
    public function testRollDiceException(): void
    {
        $this->expectException(TooManyRollsException::class);
        $this->game->startRound();
        for ($i = 0; $i < 10; $i++) {
            $this->game->rollDice();
        }
    }

    /**
    * Round is ended if player has filled their sheet/is finished
    */
    public function testRegisterActiveRollFinished(): void
    {
        $this->game->startRound();
        for ($i = 0; $i < 6; $i++) {
            $this->game->rollDice();
            $this->game->registerActiveRoll();
        }
        $this->assertTrue($this->game->roundHasEnded());
    }

    /**
    * newRound starts new round
    */
    public function testNewRound(): void
    {
        $this->game->startRound();
        for ($i = 0; $i < 6; $i++) {
            $this->game->rollDice();
            $this->game->registerActiveRoll();
        }
        $this->game->newRound();
        $this->assertFalse($this->game->roundHasEnded());
    }

    /**
    * getGoalValue returns correct value
    */
    public function testGetGoalValue(): void
    {
        $this->game->startRound();
        $this->assertSame($this->game->getGoalValue(), 6);
    }

    /**
    * getNumRollsLeft returns correct value
    */
    public function testGetNumRollsLeft(): void
    {
        $this->game->startRound();
        $this->assertSame($this->game->getNumRollsLeft(), 3);
    }

    /**
    * getCurrentRollNumber returns correct value
    */
    public function testGetCurrentRollNumber(): void
    {
        $this->game->startRound();
        $this->assertSame($this->game->getCurrentRollNumber(), 1);
    }

    /**
    * getCurrentDieValues returns correct values
    */
    public function testGetCurrentDieValues(): void
    {
        // set rand return value
        rand(-1, 5);
        $this->game->startRound();
        $this->game->rollDice();
        $this->assertSame(
            $this->game->getCurrentDieValues(),
            [5, 5, 5, 5, 5]
        );
    }

    /**
    * endRound updates round status
    */
    public function testEndRound(): void
    {
        $this->game->startRound();
        $this->game->endRound();
        $this->assertSame(
            $this->game->roundHasEnded(),
            true
        );
    }

    // /**
    // * rolling human dice produces an integer
    // */
    // public function testRollInt(): void
    // {
    //     $this->assertIsInt($this->game->rollHumanDice());
    // }
    //
    // /**
    // * autoplay after human rolls works
    // */
    // public function testAutoplay(): void
    // {
    //     // set rand return value
    //     rand(-1, 3);
    //     for ($i = 0; $i < 2; $i++) {
    //         $this->game->rollHumanDice();
    //     }
    //     $this->game->autoplay();
    //     $this->assertIsArray($this->game->getScores());
    //     $this->assertSame($this->game->getScores()['human'], 3 * 4);
    //     $this->assertSame($this->game->getScores()['computer'], 3 * 6);
    // }
    //
    // /**
    // * getWonRounds produces array of key-value pairs
    // */
    // public function testGetWonRounds(): void
    // {
    //     $this->assertIsArray($this->game->getWonRounds());
    //     $this->assertSame($this->game->getWonRounds()['human'], 0);
    //     $this->assertSame($this->game->getWonRounds()['computer'], 0);
    // }
    //
    // /**
    // * test that endRound when it's a draw increases computer number of won
    // * rounds
    // */
    // public function testEndRoundEqual(): void
    // {
    //     $this->game->endRound();
    //     $this->assertIsArray($this->game->getWonRounds());
    //     $this->assertSame($this->game->getWonRounds()['human'], 0);
    //     $this->assertSame($this->game->getWonRounds()['computer'], 1);
    // }
    //
    // /**
    // * test that endRound when it's a draw increases winner's number of
    // * won rounds
    // */
    // public function testEndRoundNonEqual(): void
    // {
    //     // only human has rolled
    //     $this->game->rollHumanDice();
    //     $this->game->endRound();
    //     $this->assertSame($this->game->getWonRounds()['human'], 1);
    //     $this->assertSame($this->game->getWonRounds()['computer'], 0);
    //
    //
    //     // only computer has rolled
    //     $this->game->newRound();
    //     $this->game->autoPlay();
    //     $this->game->endRound();
    //     $this->assertSame($this->game->getWonRounds()['human'], 1);
    //     $this->assertSame($this->game->getWonRounds()['computer'], 1);
    //
    //     // computer overshoots 21
    //     $this->game->newRound();
    //     rand(-1, 5);
    //     for ($i = 0; $i < 2; $i++) {
    //         $this->game->rollHumanDice();
    //     }
    //     $this->game->autoPlay();
    //     $this->game->endRound();
    //     $this->assertSame($this->game->getWonRounds()['human'], 2);
    //     $this->assertSame($this->game->getWonRounds()['computer'], 1);
    // }
    //
    // /**
    // * getWinnerName returns correct name
    // */
    // public function testGetWinnerName(): void
    // {
    //     // only human has rolled
    //     $this->game->rollHumanDice();
    //     $this->assertSame("Du", $this->game->getWinnerName());
    //
    //
    //     // only computer has rolled
    //     $this->game->newRound();
    //     $this->game->autoPlay();
    //     $this->game->endRound();
    //     $this->assertSame("Datorn", $this->game->getWinnerName());
    //
    //     // computer overshoots 21
    //     $this->game->newRound();
    //     rand(-1, 5);
    //     for ($i = 0; $i < 2; $i++) {
    //         $this->game->rollHumanDice();
    //     }
    //     $this->game->autoPlay();
    //     $this->assertSame("Du", $this->game->getWinnerName());
    // }
    //
    // /**
    // * roundHasEnded works
    // */
    // public function testRoundHasEnded(): void
    // {
    //     $this->game->rollHumanDice();
    //     $this->game->endRound();
    //
    //     $this->assertTrue($this->game->roundHasEnded());
    // }
}
