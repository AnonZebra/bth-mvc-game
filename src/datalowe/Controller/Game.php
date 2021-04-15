<?php

declare(strict_types=1);

namespace dtlw\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use dtlw\Dice\DiceGame;

use function Mos\Functions\{
    url,
    renderView
};

class Game
{
    public function dice(): ResponseInterface
    {
        $body = renderView("layout/dice.php");
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function blackjackShow(): ResponseInterface
    {
        if (!array_key_exists("game", $_SESSION)) {
            $_SESSION["game"] = new DiceGame();
        }
        $game = $_SESSION["game"];
        $data = [
            "scores" => $game->getScores(),
            "wonRounds" => $game->getWonRounds(),
            "winner" => $game->getWinnerName(),
            "roundHasEnded" => $game->roundHasEnded()
        ];
        if (array_key_exists("errmsg", $_SESSION)) {
            $data["errmsg"] = $_SESSION["errmsg"];
            unset($_SESSION["errmsg"]);
        }
        $body = renderView("layout/blackjack.php", $data);
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
    public function blackjackProcess(): ResponseInterface
    {
        $game = $_SESSION["game"];
        if (array_key_exists("num-dice", $_POST)) {
            $numDice = intval($_POST["num-dice"]);
            try {
                $_SESSION["game"] = new DiceGame($numDice);
            } catch (\dtlw\Dice\InvalidNumberOfDice $e) {
                $_SESSION["errmsg"] = $e->getMessage();
            }
        } elseif (array_key_exists("roll-dice", $_POST)) {
            $game->rollHumanDice();
        } elseif (array_key_exists("stay", $_POST)) {
            $game->autoPlay();
            $game->endRound();
        } elseif (array_key_exists("new-round", $_POST)) {
            $game->newRound();
        }
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(302)
            ->withHeader("Location", url("/blackjack"));
    }
}
