<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Models\Score;

class BlackjackScoresController extends Controller
{
    private function getBlackjackHiScoresData()
    {
        $dbScores = Score::with(['player', 'gameSession'])
            ->where('score', '<', 22)
            ->orderBy('score')
            ->get()
            ->toArray();
        $gameData = array_map(
            fn($s) => [
                'time' => $s['game_session']['created_at'],
                'playerName' => $s['player']['name'],
                'score' => $s['score']
            ],
            $dbScores
        );
        return $gameData;
    }

    public function blackjackScoresShow() 
    {
        $gameData = $this->getBlackjackHiScoresData();
        return View::make('blackjack-hiscores', ["gameData" => $gameData]);
    }
}
