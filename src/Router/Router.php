<?php

declare(strict_types=1);

namespace Mos\Router;

use dtlw\Dice\BlackjackGame as BlackjackGame;

use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
};

/**
 * Class Router.
 */
class Router
{
    public static function dispatch(string $method, string $path): void
    {
        if ($method === "GET" && $path === "/") {
            $data = [
                "header" => "Index page",
                "message" => "Hello, this is the index page, rendered as a layout.",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } elseif ($method === "GET" && $path === "/session") {
            $body = renderView("layout/session.php");
            sendResponse($body);
            return;
        } elseif ($method === "GET" && $path === "/session/destroy") {
            destroySession();
            redirectTo(url("/session"));
            return;
        } elseif ($method === "GET" && $path === "/debug") {
            $body = renderView("layout/debug.php");
            sendResponse($body);
            return;
        } elseif ($method === "GET" && $path === "/twig") {
            $data = [
                "header" => "Twig page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderTwigView("index.html", $data);
            sendResponse($body);
            return;
        } elseif ($method === "GET" && $path === "/some/where") {
            $data = [
                "header" => "Rainbow page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } elseif ($method === "GET" && $path === "/dice") {
            $body = renderView("layout/dice.php");
            sendResponse($body);
            return;
        } elseif ($method === "GET" && $path === "/blackjack") {
            // unset($_SESSION["game"]);
            if (!array_key_exists("game", $_SESSION)) {
                $_SESSION["game"] = new BlackjackGame();
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
            sendResponse($body);
            return;
        } elseif ($method === "POST" && $path === "/blackjack") {
            $game = $_SESSION["game"];
            if (array_key_exists("num-dice", $_POST)) {
                $numDice = intval($_POST["num-dice"]);
                try {
                    $_SESSION["game"] = new BlackjackGame($numDice);
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
            redirectTo("blackjack");
            return;
        }

        $data = [
            "header" => "404",
            "message" => (
                "The page you are requesting is not here." .
                "You may also checkout the HTTP response code, it should be 404."
            ),
        ];
        $body = renderView("layout/page.php", $data);
        sendResponse($body, 404);
    }
}
