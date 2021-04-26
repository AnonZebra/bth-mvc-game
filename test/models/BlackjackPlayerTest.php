<?php

namespace dtlw\Dice;

use PHPUnit\Framework\TestCase;

class BlackjackPlayerTest extends TestCase
{
    /**
    * @var BlackjackPlayer $player A blackjack player with 2 dice.
    */
    private $player;
    private const NUM_DICE = 2;

    public function setUp(): void
    {
        $this->player = new BlackjackPlayer(self::NUM_DICE);
    }

    public function tearDown(): void
    {
        // reset rand return value
        rand(-2, 0);
    }

    /**
    * getScore returns correct value after roll.
    */
    public function testGetLastRollAfterRoll(): void
    {
        $ROLL_VAL = 5;
        // set rand return value
        rand(-1, $ROLL_VAL);

        $this->player->roll();

        $this->assertSame($this->player->getScore(), self::NUM_DICE * $ROLL_VAL);
    }

    /**
    * autoPlay plays according to passed values.
    */
    public function testAutoPlay(): void
    {
        $ROLL_VAL = 5;
        // set rand return value
        rand(-1, $ROLL_VAL);

        $this->player->autoPlay(19);

        $this->assertSame($this->player->getScore(), 20);
    }

    /**
    * autoPlay doesn't change score if passed value higher than 21
    */
    public function testAutoPlayAlreadyWon(): void
    {
        $ROLL_VAL = 5;
        // set rand return value
        rand(-1, $ROLL_VAL);

        $this->player->autoPlay(22);

        $this->assertSame($this->player->getScore(), 0);
    }
}
