<?php

declare(strict_types=1);

namespace App\Models\Dice;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DieFactoryTest extends TestCase
{
    /**
    * @var DieFactory $plainF A plain die factory
    * @var DieFactory $graphF A graphical die factory
    */
    private $plainF;
    private $graphF;

    /**
    * Fixture setup.
    */
    public function setUp(): void
    {
        $this->plainF = new DieFactory('plain');
        $this->graphF = new DieFactory('graphical');
    }

    /**
    * Trying to construct invalid factory throws exception
    */
    public function testGetLastRollException(): void
    {
        $this->expectException(InvalidDieTypeException::class);

        new DieFactory("invalidType");
    }

    /**
    * Plain factory produces dice of correct type.
    */
    public function testPlainOutput(): void
    {
        $producedDie = $this->plainF->make();

        $this->assertInstanceOf(Dice::class, $producedDie);
    }

    /**
    * Graphical factory produces dice of correct type.
    */
    public function testGraphicalOutput(): void
    {
        $producedDie = $this->graphF->make();

        $this->assertInstanceOf(GraphicalDice::class, $producedDie);
    }

    /**
    * Graphical factory throws exception if die type has been
    * set to invalid value (this isn't possible in practice,
    * the exception-throwing is only there to please the linter,
    * but I'm adding this test to reach 100% coverage)
    */
    public function testInvalidOutputException(): void
    {
        $stub = new ReflectionClass($this->plainF);

        $typeProp = $stub->getProperty('dieType');
        $typeProp->setAccessible(true);
        $typeProp->setValue($this->plainF, 'invalidType');

        $makeMethod = $stub->getMethod('make');

        $this->expectException(InvalidDieTypeException::class);
        $makeMethod->invoke($this->plainF);
    }
}
