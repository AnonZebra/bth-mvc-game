<?php

declare(strict_types=1);

namespace App\Models\Dice;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function rand as defaultRand;

// 'mocking' rand to make tests using roll method entirely deterministic.
// pass in -1 as first argument to set the return value to the second argument.
// pass in -2 as first argument to make this function simply call the
// builtin rand function.
// https://www.schmengler-se.de/en/2011/03/php-mocking-built-in-functions-like-time-in-unit-tests/
function rand(int $val1, int $val2): int
{
    static $returnVal = 0;
    static $useBuiltin = true;

    if ($val1 == -1) {
        $returnVal = $val2;
        $useBuiltin = false;
    }
    if ($val1 == -2) {
        $useBuiltin = true;
    }
    if ($useBuiltin) {
        return defaultRand($val1, $val2);
    }
    return $returnVal;
}

class GraphicalDiceTest extends TestCase
{
    /**
    * @var GraphicalDice $dDie A default die, created without specifying arguments
    */
    private $dDie;

    /**
    * Fixture setup.
    */
    public function setUp(): void
    {
        $this->dDie = new GraphicalDice();
    }

    public function tearDown(): void
    {
        // reset rand() function
        rand(-2, 0);
    }

    /**
    * setNumSides does nothing, specifically does not update the number of sides
    */
    public function testSetNumSides(): void
    {
        // set rand() return value
        rand(-1, 4);

        $this->dDie->setNumSides(4);
        $this->dDie->roll();

        $this->assertSame($this->dDie->getLastRoll(), 4);
    }

    /**
    * getFaceImg throws exception when no roll has been made
    */
    public function testGetFaceImgException(): void
    {
        $this->expectException(HaventRolledYetException::class);
        $this->dDie->getFaceImg();
    }

    // DISABLED for now, since I haven't been able to figure out
    // how to mock an app/ensure that the `url()` function will work
    // /**
    // * getFaceImg returns correct string
    // */
    // public function testGetFaceImg(): void
    // {
    //     // "mocking" global variable keys, in order to make getBaseUrl
    //     // output deterministic/not produce any errors
    //     $_SERVER["REQUEST_SCHEME"] = "foo";
    //     $_SERVER["SERVER_NAME"] = "bar";
    //     $_SERVER["SERVER_PORT"] = "9001";
    //     $_SERVER["REQUEST_URI"] = "/baz";

    //     // set rand() return value
    //     rand(-1, 6);

    //     $this->dDie->roll();
    //     // echo getBaseUrl();

    //     $this->assertSame(
    //         url("/img/die/die6.svg"),
    //         $this->dDie->getFaceImg()
    //     );
    // }
}
