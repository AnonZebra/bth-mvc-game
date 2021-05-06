<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Http\RedirectResponse;

use App\Models\Dice\BlackjackGame;

class BlackjackController extends Controller
{
    private function getBlackjackGame(): BlackjackGame
    {
        if (!session()->has('blackjack-game')) {
            session(['blackjack-game' => new BlackjackGame()]);
        }
        return session('blackjack-game');
    }

    public function blackjackShow() 
    {
        $game = self::getBlackjackGame();
        $viewVals = [
            "scores" => $game->getScores(),
            "wonRounds" => $game->getWonRounds(),
            "winner" => $game->getWinnerName(),
            "roundHasEnded" => $game->roundHasEnded()
        ];
        return View::make('blackjack', $viewVals);
    }

    public function blackjackProcess(Request $request): RedirectResponse
    {
        $game = self::getBlackjackGame();
        if ($request->input('num-dice')) {
            $numDice = intval($request->input('num-dice'));
            try {
                session(['blackjack-game' => new BlackjackGame($numDice)]);
            } catch (\App\Models\Dice\InvalidNumberOfDice $e) {
                session(['errmsg' => $e->getMessage()]);
            }
        } elseif ($request->input('roll-dice')) {
            $game->rollHumanDice();
        } elseif ($request->input('stay')) {
            $game->autoPlay();
            $game->endRound();
        } elseif ($request->input('new-round')) {
            $game->newRound();
        }
        return redirect('/blackjack');
    }
}
