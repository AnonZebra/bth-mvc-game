<?php

declare(strict_types=1);

namespace App\Models\Dice;

use PHPUnit\Framework\TestCase;

// 'mocking' rand to make tests using roll method deterministic, but with
// output that varies from time to time
// https://www.schmengler-se.de/en/2011/03/php-mocking-built-in-functions-like-time-in-unit-tests/


class DiceHandTest extends TestCase
{
    /**
    * @var DiceHand $plainH A hand of 3 plain dice
    * @var DiceHand $plainH A hand of 3 graphical dice
    */
    private $plainH;
    private $graphH;
    private const NUM_DICE = 3;

    /**
    * Fixture setup.
    */
    public function setUp(): void
    {
        $this->plainH = new DiceHand(self::NUM_DICE, new DieFactory('plain'));
        $this->graphH = new DiceHand(self::NUM_DICE, new DieFactory('graphical'));
    }

    public function tearDown(): void
    {
        // reset rand() function
        rand(-2, 0);
    }

    /**
    * getLastRoll throws exception when no roll has been made.
    */
    public function testGetLastRollException(): void
    {
        $this->expectException(HaventRolledYetException::class);

        $this->plainH->getLastRoll();
    }

    /**
    * A rolled hand's getLastRoll method returns an array of integer values.
    */
    public function testGetLastRollAfterRoll(): void
    {
        $this->plainH->roll();

        $valArr = $this->plainH->getLastRoll();

        foreach ($valArr as $val) {
            $this->assertIsInt($val);
        }

        $this->assertIsArray($valArr);
    }

    /**
    * Rolling a hand a second time with skip indices set ensures that
    * specified dice maintain the same values. (this test is _not_ deterministic,
    * and will not always correctly test the method).
    */
    public function testRollSkip(): void
    {
        // setting rand() return value
        rand(-1, 6);
        $this->plainH->roll();
        $SKIP_INDEX = 1;

        $saveVal = $this->plainH->getLastRoll()[$SKIP_INDEX];

        rand(-1, 5);
        $this->plainH->roll([$SKIP_INDEX]);

        $afterVal = $this->plainH->getLastRoll()[$SKIP_INDEX];

        $this->assertSame($saveVal, $afterVal);
    }

    // DISABLED for now, since I haven't been able to figure out
    // how to mock an app/ensure that the `url()` function will work
    // /**
    // * A rolled hand's getFaceImgs method returns an array of strings
    // * (if the dice are graphical).
    // */
    // public function testGraphGetFaceImgsAfterRoll(): void
    // {
    //     // "mocking" global variable keys, in order to make getBaseUrl
    //     // output deterministic/not produce any errors
    //     $_SERVER["REQUEST_SCHEME"] = "foo";
    //     $_SERVER["SERVER_NAME"] = "bar";
    //     $_SERVER["SERVER_PORT"] = "9001";
    //     $_SERVER["REQUEST_URI"] = "/baz";

    //     $this->graphH->roll();

    //     $valArr = $this->graphH->getFaceImgs();

    //     foreach ($valArr as $val) {
    //         $this->assertIsString($val);
    //     }

    //     $this->assertIsArray($valArr);
    // }

    /**
    * A rolled hand's getFaceImgs method produces an error,
    * if the dice are plain.
    */
    public function testPlainGetFaceImgsAfterRoll(): void
    {
        // "mocking" global variable keys, in order to make getBaseUrl
        // output deterministic/not produce any errors
        $_SERVER["REQUEST_SCHEME"] = "foo";
        $_SERVER["SERVER_NAME"] = "bar";
        $_SERVER["SERVER_PORT"] = "9001";
        $_SERVER["REQUEST_URI"] = "/baz";

        $this->plainH->roll();

        $this->expectException(InvalidDieTypeException::class);

        $this->plainH->getFaceImgs();
    }

    /**
    * A non-rolled hand's getFaceImgs method produces an error.
    */
    public function testPlainGetFaceImgsNotRolled(): void
    {
        // "mocking" global variable keys, in order to make getBaseUrl
        // output deterministic/not produce any errors
        $_SERVER["REQUEST_SCHEME"] = "foo";
        $_SERVER["SERVER_NAME"] = "bar";
        $_SERVER["SERVER_PORT"] = "9001";
        $_SERVER["REQUEST_URI"] = "/baz";

        $this->expectException(HaventRolledYetException::class);

        $this->graphH->getFaceImgs();
    }

    /**
    * A rolled hand's getLastRollTotal method returns roll total.
    */
    public function testGetLastRollTotalAfterRoll(): void
    {
        $ROLL_VAL = 5;

        // set rand return value
        rand(-1, $ROLL_VAL);

        $this->plainH->roll();

        $this->assertSame(
            self::NUM_DICE * $ROLL_VAL,
            $this->plainH->getLastRollTotal()
        );
    }

    /**
    * Exception is thrown/bubbled by getLastRollTotal if no roll has been made.
    */
    public function testGetLastRollTotalNoRoll(): void
    {
        $this->expectException(HaventRolledYetException::class);

        $this->plainH->getLastRollTotal();
    }
}
