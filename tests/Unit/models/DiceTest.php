<?php

declare(strict_types=1);

namespace App\Models\Dice;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DiceTest extends TestCase
{
    /**
    * @var Dice $dDie A default die, created without specifying arguments
    */
    private $dDie;

    /**
    * Fixture setup.
    */
    public function setUp(): void
    {
        $this->dDie = new Dice();
    }

    /**
    * getLastRoll throws exception when no roll has been made.
    */
    public function testGetLastRollException(): void
    {
        $this->expectException(HaventRolledYetException::class);

        $this->dDie->getLastRoll();
    }

    /**
    * A rolled die has a last roll value.
    */
    public function testGetLastRollAfterRoll(): void
    {
        $this->dDie->roll();

        $this->assertIsInt($this->dDie->getLastRoll());
    }

    /**
    * setNumSides does update the number of sides.
    */
    public function testSetNumSides(): void
    {
        $this->dDie->setNumSides(1);
        $this->dDie->roll();

        $this->assertSame($this->dDie->getLastRoll(), 1);
    }

    /**
    * setNumSides does update the number of sides
    * (testing by 'cheating' using ReflectionClass,
    * https://blog.buzachis-aris.com/2016/02/accessing-private-
    * or-protected-attributes-and-methods-in-php/)
    */
    public function testSetNumSidesCheating(): void
    {
        $this->dDie->setNumSides(1);

        $reflector = new ReflectionClass($this->dDie);
        $prop = $reflector->getProperty('numSides');
        $prop->setAccessible(true);

        $this->assertSame($prop->getValue($this->dDie), 1);
    }
}
