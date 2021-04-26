<?php

namespace dtlw\Dice;

use PHPUnit\Framework\TestCase;

class BlackjackGameTest extends TestCase
{
    /**
    * @var BlackjackGame $player A blackjack game where each player has 2 dice.
    */
    private $game;
    private const NUM_DICE = 2;

    public function setUp(): void
    {
        $this->game = new BlackjackGame(self::NUM_DICE);
    }

    public function tearDown(): void
    {
        // reset rand return value
        rand(-2, 0);
    }

    /**
    * constructing a game with an invalid number of dice produces an
    * exception
    */
    public function testInvalidConstruction(): void
    {
        $this->expectException(InvalidNumberOfDice::class);

        new BlackjackGame(9001);
    }

    /**
    * rolling human dice produces an integer
    */
    public function testRollInt(): void
    {
        $this->assertIsInt($this->game->rollHumanDice());
    }

    /**
    * autoplay after human rolls works
    */
    public function testAutoplay(): void
    {
        // set rand return value
        rand(-1, 3);
        for ($i = 0; $i < 2; $i++) {
            $this->game->rollHumanDice();
        }
        $this->game->autoplay();
        $this->assertIsArray($this->game->getScores());
        $this->assertSame($this->game->getScores()['human'], 3 * 4);
        $this->assertSame($this->game->getScores()['computer'], 3 * 6);
    }

    /**
    * getWonRounds produces array of key-value pairs
    */
    public function testGetWonRounds(): void
    {
        $this->assertIsArray($this->game->getWonRounds());
        $this->assertSame($this->game->getWonRounds()['human'], 0);
        $this->assertSame($this->game->getWonRounds()['computer'], 0);
    }

    /**
    * test that endRound when it's a draw increases computer number of won
    * rounds
    */
    public function testEndRoundEqual(): void
    {
        $this->game->endRound();
        $this->assertIsArray($this->game->getWonRounds());
        $this->assertSame($this->game->getWonRounds()['human'], 0);
        $this->assertSame($this->game->getWonRounds()['computer'], 1);
    }

    /**
    * test that endRound when it's a draw increases winner's number of
    * won rounds
    */
    public function testEndRoundNonEqual(): void
    {
        // only human has rolled
        $this->game->rollHumanDice();
        $this->game->endRound();
        $this->assertSame($this->game->getWonRounds()['human'], 1);
        $this->assertSame($this->game->getWonRounds()['computer'], 0);


        // only computer has rolled
        $this->game->newRound();
        $this->game->autoPlay();
        $this->game->endRound();
        $this->assertSame($this->game->getWonRounds()['human'], 1);
        $this->assertSame($this->game->getWonRounds()['computer'], 1);

        // computer overshoots 21
        $this->game->newRound();
        rand(-1, 5);
        for ($i = 0; $i < 2; $i++) {
            $this->game->rollHumanDice();
        }
        $this->game->autoPlay();
        $this->game->endRound();
        $this->assertSame($this->game->getWonRounds()['human'], 2);
        $this->assertSame($this->game->getWonRounds()['computer'], 1);
    }

    /**
    * getWinnerName returns correct name
    */
    public function testGetWinnerName(): void
    {
        // only human has rolled
        $this->game->rollHumanDice();
        $this->assertSame("Du", $this->game->getWinnerName());


        // only computer has rolled
        $this->game->newRound();
        $this->game->autoPlay();
        $this->game->endRound();
        $this->assertSame("Datorn", $this->game->getWinnerName());

        // computer overshoots 21
        $this->game->newRound();
        rand(-1, 5);
        for ($i = 0; $i < 2; $i++) {
            $this->game->rollHumanDice();
        }
        $this->game->autoPlay();
        $this->assertSame("Du", $this->game->getWinnerName());
    }

    /**
    * roundHasEnded works
    */
    public function testRoundHasEnded(): void
    {
        $this->game->rollHumanDice();
        $this->game->endRound();

        $this->assertTrue($this->game->roundHasEnded());
    }
}
