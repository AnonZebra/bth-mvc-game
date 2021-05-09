<?php

namespace App\Models\Dice;

use Tests\TestCase;

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
}
