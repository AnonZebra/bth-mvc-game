<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'score',
        'player_id',
        'session_id',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function gameSession()
    {
        return $this->belongsTo(GameSession::class, 'session_id', 'id', 'game_sessions');
    }
}
