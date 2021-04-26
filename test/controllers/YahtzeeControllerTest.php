<?php

namespace dtlw\Controller;

use PHPUnit\Framework\TestCase;
use dtlw\Dice\YahtzeeGame;

use function Mos\Functions\renderView;
use function Mos\Functions\url;

class YahtzeeControllerTest extends TestCase
{
    /**
    * @var YahtzeeController $c Default instance of controller
    */
    private YahtzeeController $c;

    public static function setUpBeforeClass(): void
    {
        $_SERVER["REQUEST_SCHEME"] = "foo";
        $_SERVER["SERVER_NAME"] = "bar";
        $_SERVER["SERVER_PORT"] = "9001";
        $_SERVER["REQUEST_URI"] = "/baz";
        // define("INSTALL_PATH", realpath(__DIR__ . "/../.."));
    }

    public function setUp(): void
    {
        $_SESSION = array();
        $_POST = array();
        $this->c = new YahtzeeController();
    }

    /**
    * yahtzeeShow uses correct view
    */
    public function testYahtzeeShow(): void
    {
        $response = $this->c->yahtzeeShow();

        $this->assertStringContainsString(
            "Yahtzee",
            (string) $response->getBody(),
        );
    }

    /**
    * yahtzeeShow includes error message if one is available
    */
    public function testYahtzeeShowErrmsg(): void
    {
        $errmsg = "FOO ERROR";
        $_SESSION["errmsg"] = $errmsg;

        $response = $this->c->yahtzeeShow();

        $this->assertStringContainsString(
            $errmsg,
            (string) $response->getBody(),
        );
    }

    /**
    * yahtzeeProcess returns correct header
    */
    public function testYahtzeeProcessHeader(): void
    {
        $yahtzeeGame = new YahtzeeGame();
        $_SESSION['game'] = $yahtzeeGame;

        $response = $this->c->yahtzeeProcess();

        $expHeaderArr = ["Location" => [url("/yahtzee")]];

        $this->assertSame(
            $expHeaderArr,
            $response->getHeaders()
        );
    }

    /**
    * yahtzeeProcess runs and makes roll happen
    * when roll-dice parameter is in request
    */
    public function testYahtzeeProcessRoll(): void
    {
        $game = new YahtzeeGame();
        $_SESSION['yahtzee'] = $game;
        $_POST['roll-dice'] = 1;

        $response = $this->c->yahtzeeProcess();

        $this->assertSame(
            2,
            $game->getNumRollsLeft()
        );
    }

    /**
    * yahtzeeProcess runs and makes roll happen
    * when roll-dice parameter and 'dice' parameter are in request
    */
    public function testYahtzeeProcessRollSpecific(): void
    {
        $game = new YahtzeeGame();
        $_SESSION['yahtzee'] = $game;
        $_POST['roll-dice'] = 1;
        $response = $this->c->yahtzeeProcess();

        $_POST['dice'] = [1, 3, 4];
        $response = $this->c->yahtzeeProcess();

        $this->assertSame(
            1,
            $game->getNumRollsLeft()
        );
    }

    /**
    * yahtzeeProcess correctly registers roll
    */
    public function testYahtzeeProcessRegister(): void
    {
        $game = new YahtzeeGame();
        $_SESSION['yahtzee'] = $game;
        $_POST['roll-dice'] = 1;

        $this->c->yahtzeeProcess();

        $_POST['dice'] = [1, 3, 4];
        $this->c->yahtzeeProcess();

        $_POST = array();
        $_POST['register'] = 1;
        $response = $this->c->yahtzeeProcess();

        $this->assertSame(
            3,
            $game->getNumRollsLeft()
        );
    }

    /**
    * yahtzeeProcess correctly starts new round
    */
    public function testYahtzeeProcessNewRound(): void
    {
        $game = new YahtzeeGame();
        $_SESSION['yahtzee'] = $game;

        for ($i = 0; $i < 6; $i++) {
            $_POST['roll-dice'] = 1;
            $this->c->yahtzeeProcess();
            $_POST = array();
            $_POST['register'] = 1;
            $this->c->yahtzeeProcess();
        }

        var_dump($game->getGoalValue());

        $_POST = array();

        $_POST["new-round"] = 1;

        $this->c->yahtzeeProcess();

        $this->assertSame(
            3,
            $game->getNumRollsLeft()
        );
    }

    // /**
    // * yahtzeeProcess ends round if request includes stay parameter
    // */
    // public function testYahtzeeProcessStay(): void
    // {
    //     $blackGame = new YahtzeeGame();
    //     $_SESSION['game'] = $blackGame;
    //     $_POST['stay'] = 1;
    //
    //     $response = $this->c->yahtzeeProcess();
    //
    //     $this->assertTrue($_SESSION['game']->roundHasEnded());
    // }
    //
    // /**
    // * yahtzeeProcess starts new round if request includes new-round parameter
    // */
    // public function testYahtzeeProcessNewRound(): void
    // {
    //     $blackGame = new YahtzeeGame();
    //     $_SESSION['game'] = $blackGame;
    //     $_POST['stay'] = 1;
    //     $this->c->yahtzeeProcess();
    //
    //     $_POST = array();
    //     $_POST['new-round'] = 1;
    //     $response = $this->c->yahtzeeProcess();
    //
    //     $this->assertFalse($_SESSION['game']->roundHasEnded());
    // }
}
