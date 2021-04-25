<?php

declare(strict_types=1);

namespace dtlw\Dice;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function Mos\Functions\{
    getBaseUrl
};

// 'mocking' rand to make this tests using roll method entirely deterministic.
// https://www.schmengler-se.de/en/2011/03/php-mocking-built-in-functions-like-time-in-unit-tests/
function rand(int $val1, int $val2): int
{
    return $val2;
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

    /**
    * setNumSides does nothing, specifically does not update the number of sides
    */
    public function testSetNumSides(): void
    {
        $this->dDie->setNumSides(4);
        $this->dDie->roll();

        $this->assertSame($this->dDie->getLastRoll(), 6);
    }

    /**
    * getFaceImg throws exception when no roll has been made
    */
    public function testGetFaceImgException(): void
    {
        $this->expectException(HaventRolledYetException::class);
        $this->dDie->getFaceImg();
    }

    /**
    * getFaceImg returns correct string
    */
    public function testGetFaceImg(): void
    {
        // "mocking" global variable keys, in order to make getBaseUrl
        // output deterministic/not produce any errors
        $_SERVER["REQUEST_SCHEME"] = "foo";
        $_SERVER["SERVER_NAME"] = "bar";
        $_SERVER["SERVER_PORT"] = "9001";
        $_SERVER["REQUEST_URI"] = "/baz";

        $this->dDie->roll();
        // echo getBaseUrl();

        $this->assertSame(
            $this->dDie->getFaceImg(),
            getBaseUrl() . "/img/die/die6.svg"
        );
    }
}
