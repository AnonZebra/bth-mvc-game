<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Dice\BlackjackGame;
use App\Http\Controllers\BlackjackController;

class BlackjackControllerTest extends TestCase
{
    // use RefreshDatabase;
    /**
    * blackjackShow uses correct view
    */
    public function testBlackjackShow(): void
    {
        $response = $this->get('/blackjack');

        $response->assertStatus(200);

        $response->assertSee(
            "Dice Blackjack",
        );
    }

        /**
    * blackjackShow includes error message if one is available
    */
    public function testBlackjackShowErrmsg(): void
    {
        $errmsg = "FOO ERROR";
        session(["errmsg" => $errmsg]);

        $response = $this->get('/blackjack');

        $response->assertSee(
            $errmsg
        );
    }

    /**
    * blackjackProcess redirects user to correct URI
    */
    public function testBlackjackRedirect(): void
    {
        $response = $this->post('/blackjack');

        $response->assertRedirect('/blackjack');
    }

    /**
    * blackjackProcess runs and adds game to session
    * when valid num-dice parameter is included
    */
    public function testBlackjackProcessNewGame(): void
    {
        $this->post('/blackjack', [
            'num-dice' => 2
        ]);

        $this->assertInstanceOf(BlackjackGame::class, session("blackjack-game"));
    }

    /**
    * blackjackProcess adds error message to session when incorrect
    * number of dice is specified
    */
    public function testBlackjackProcessInvalidGame(): void
    {
        $this->post('/blackjack', [
            'num-dice' => 5
        ]);

        $this->assertTrue(session()->has("errmsg"));
    }

    /**
    * blackjackProcess runs and makes roll happen
    * when roll-dice parameter is in request
    */
    public function testBlackjackProcessRoll(): void
    {
        $this->post('/blackjack', [
            'roll-dice' => 1
        ]);

        $this->assertSame("You", session('blackjack-game')->getWinnerName());
    }

    /**
    * blackjackProcess ends round if request includes stay parameter
    */
    public function testBlackjackProcessStay(): void
    {
        $this->post('/blackjack', [
            'stay' => 1
        ]);

        $this->assertTrue(session('blackjack-game')->roundHasEnded());
    }

        /**
    * blackjackProcess starts new round if request includes new-round parameter
    */
    public function testBlackjackProcessNewRound(): void
    {
        $this->post('/blackjack', [
            'stay' => 1
        ]);

        $this->post('/blackjack', [
            'new-round' => 1
        ]);

        $this->assertFalse(session('blackjack-game')->roundHasEnded());
    }
}
