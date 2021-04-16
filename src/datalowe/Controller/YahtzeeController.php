<?php

declare(strict_types=1);

namespace dtlw\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use dtlw\Dice\YahtzeeGame;

use function Mos\Functions\{
    url,
    renderView
};

class YahtzeeController
{
    public function dice(): ResponseInterface
    {
        $body = renderView("layout/dice.php");
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function yahtzeeShow(): ResponseInterface
    {
        // unset($_SESSION["yahtzee"]);
        if (!array_key_exists("yahtzee", $_SESSION)) {
            $_SESSION["yahtzee"] = new YahtzeeGame();
        }
        $game = $_SESSION["yahtzee"];
        $data = [
            "scores" => $game->getScores(),
            "roundHasEnded" => $game->roundHasEnded(),
            "numRollsLeft" => $game->getNumRollsLeft(),
            "currentRoll" => $game->getCurrentRollNumber(),
            "dieVals" => $game->getCurrentDieValues(),
            "goalValue" => $game->getGoalValue()
        ];
        if (array_key_exists("errmsg", $_SESSION)) {
            $data["errmsg"] = $_SESSION["errmsg"];
            unset($_SESSION["errmsg"]);
        }
        $body = renderView("layout/yahtzee.php", $data);
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
    public function yahtzeeProcess(): ResponseInterface
    {
        $game = $_SESSION["yahtzee"];
        if (array_key_exists("num-dice", $_POST)) {
            $numDice = intval($_POST["num-dice"]);
            try {
                $_SESSION["yahtzee"] = new YahtzeeGame($numDice);
            } catch (\dtlw\Dice\InvalidNumberOfDice $e) {
                $_SESSION["errmsg"] = $e->getMessage();
            }
        } elseif (array_key_exists("roll-dice", $_POST)) {
            if (array_key_exists('dice', $_POST)) {
                $_SESSION["errmsg"] = implode(" : ", $_POST['dice']);
                $pickedDice = array_map(
                    fn($x) => intval($x),
                    $_POST['dice']
                );
                $game->rollDice($pickedDice);
            } else {
                $game->rollDice();
            }
        } elseif (array_key_exists("register", $_POST)) {
            $game->registerActiveRoll();
        } elseif (array_key_exists("new-round", $_POST)) {
            $game->newRound();
        }
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(302)
            ->withHeader("Location", url("/yahtzee"));
    }
}
