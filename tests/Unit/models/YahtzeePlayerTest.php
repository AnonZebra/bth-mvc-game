<?php

namespace App\Models\Dice;

use PHPUnit\Framework\TestCase;

class YahtzeePlayerTest extends TestCase
{
    /**
    * @var YahtzeePlayer $player A Yahtzee player.
    */
    private $player;
    private const NUM_DICE = 2;

    public function setUp(): void
    {
        $this->player = new YahtzeePlayer();
    }

    public function tearDown(): void
    {
        // reset rand return value
        rand(-2, 0);
    }

    /**
    * rolling increases number of consecutive rolls
    */
    public function testGetLastRollAfterRoll(): void
    {
        $this->player->roll();
        $this->player->roll();

        $this->assertSame($this->player->getNumConsecutiveRolls(), 2);
    }

    /**
    * roll registration correctly increases score
    */
    public function testRegisterRoll(): void
    {
        $ROLL_VAL = 6;
        // set rand return value
        rand(-1, $ROLL_VAL);
        $this->player->roll();
        $this->player->registerRoll();

        $this->assertSame($this->player->getScore(), 5 * $ROLL_VAL);

        $this->player->roll();
        $this->player->registerRoll();

        $this->assertSame($this->player->getScore(), 5 * $ROLL_VAL);
    }

    /**
    * score reset works correctly
    */
    public function testResetScore(): void
    {
        $ROLL_VAL = 6;
        // set rand return value
        rand(-1, $ROLL_VAL);
        $this->player->roll();
        $this->player->registerRoll();
        $this->player->resetScore();

        $this->assertSame($this->player->getScore(), 0);
    }

    /**
    * getCurrentDieValues returns correct array after roll
    */
    public function testGetCurrentDieValuesAfterRoll(): void
    {
        $ROLL_VAL = 6;
        // set rand return value
        rand(-1, $ROLL_VAL);
        $this->player->roll();

        $valArr = $this->player->getCurrentDieValues();

        foreach ($valArr as $val) {
            $this->assertIsInt($val);
            $this->assertSame($val, $ROLL_VAL);
        }

        $this->assertIsArray($valArr);
    }

    /**
    * finished function correctly returns false when not finished
    */
    public function testFinishedWhenNot(): void
    {
        $this->player->roll();

        $this->assertFalse($this->player->finished());
    }

    /**
    * finished function correctly returns false when finished
    */
    public function testFinishedWhenIs(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->player->roll();
            $this->player->registerRoll();
        }

        $this->assertTrue($this->player->finished());
    }

    /**
    * getGoalValue returns correct value
    */
    public function testGetGoalValue(): void
    {
        $this->assertSame($this->player->getGoalValue(), 6);
    }

    /**
    * getGoalValue returns 0 when sheet has been filled
    */
    public function testGetGoalValueFullSheetException(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->player->roll();
            $this->player->registerRoll();
        }

        $this->assertSame($this->player->getGoalValue(), 0);
    }

    /**
    * when registered roll values exceed 62, a bonus of 50 is applied
    */
    public function testRegisterRollBonus(): void
    {
        $correctVal = 50;
        for ($i = 6; $i > 0; $i--) {
            // set rand return value
            rand(-1, $i);
            $this->player->roll();
            $this->player->registerRoll();
            $correctVal += $i * 5;
        }

        $this->assertSame($this->player->getScore(), $correctVal);
    }
}
