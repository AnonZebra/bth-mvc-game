<?php

namespace dtlw\Controller;

use PHPUnit\Framework\TestCase;
use dtlw\Dice\BlackjackGame;

use function Mos\Functions\renderView;
use function Mos\Functions\url;

class BlackjackControllerTest extends TestCase
{
    /**
    * @var BlackjackController $c Default instance of controller
    */
    private BlackjackController $c;

    public static function setUpBeforeClass(): void
    {
        $_SERVER["REQUEST_SCHEME"] = "foo";
        $_SERVER["SERVER_NAME"] = "bar";
        $_SERVER["SERVER_PORT"] = "9001";
        $_SERVER["REQUEST_URI"] = "/baz";
    }

    public function setUp(): void
    {
        $_SESSION = array();
        $_POST = array();
        $this->c = new BlackjackController();
    }

    public static function tearDownBeforeClass(): void
    {
        $_SERVER = array();
        $_POST = array();
    }

    /**
    * blackjackShow uses correct view
    */
    public function testBlackjackShow(): void
    {
        $response = $this->c->blackjackShow();

        $this->assertStringContainsString(
            "Blackjack with Dice",
            (string) $response->getBody(),
        );
    }

    /**
    * blackjackShow includes error message if one is available
    */
    public function testBlackjackShowErrmsg(): void
    {
        $errmsg = "FOO ERROR";
        $_SESSION["errmsg"] = $errmsg;

        $response = $this->c->blackjackShow();

        $this->assertStringContainsString(
            $errmsg,
            (string) $response->getBody(),
        );
    }

    /**
    * blackjackProcess returns correct header
    */
    public function testBlackjackProcessHeader(): void
    {
        $blackGame = new BlackjackGame();
        $_SESSION['game'] = $blackGame;

        $response = $this->c->blackjackProcess();

        $expHeaderArr = ["Location" => [url("/blackjack")]];

        $this->assertSame(
            $expHeaderArr,
            $response->getHeaders()
        );
    }

    /**
    * blackjackProcess runs and adds game to session
    * when num-dice parameter is included
    */
    public function testBlackjackProcessNewGame(): void
    {
        $blackGame = new BlackjackGame();
        $_SESSION['game'] = $blackGame;
        $_POST['num-dice'] = 2;

        $response = $this->c->blackjackProcess();

        $this->assertInstanceOf(BlackjackGame::class, $_SESSION["game"]);
    }

    /**
    * blackjackProcess adds error message to session when incorrect
    * number of dice is specified
    */
    public function testBlackjackProcessInvalidGame(): void
    {
        $_POST['num-dice'] = 5;

        $response = $this->c->blackjackProcess();

        $this->assertTrue(array_key_exists("errmsg", $_SESSION));
    }

    /**
    * blackjackProcess runs and makes roll happen
    * when roll-dice parameter is in request
    */
    public function testBlackjackProcessRoll(): void
    {
        $blackGame = new BlackjackGame();
        $_SESSION['game'] = $blackGame;
        $_POST['roll-dice'] = 1;

        $response = $this->c->blackjackProcess();

        $this->assertSame("Du", $_SESSION['game']->getWinnerName());
    }

    /**
    * blackjackProcess ends round if request includes stay parameter
    */
    public function testBlackjackProcessStay(): void
    {
        $blackGame = new BlackjackGame();
        $_SESSION['game'] = $blackGame;
        $_POST['stay'] = 1;

        $response = $this->c->blackjackProcess();

        $this->assertTrue($_SESSION['game']->roundHasEnded());
    }

    /**
    * blackjackProcess starts new round if request includes new-round parameter
    */
    public function testBlackjackProcessNewRound(): void
    {
        $blackGame = new BlackjackGame();
        $_SESSION['game'] = $blackGame;
        $_POST['stay'] = 1;
        $this->c->blackjackProcess();

        $_POST = array();
        $_POST['new-round'] = 1;
        $response = $this->c->blackjackProcess();

        $this->assertFalse($_SESSION['game']->roundHasEnded());
    }
}
