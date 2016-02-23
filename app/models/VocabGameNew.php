<?php

use Illuminate\Database\Eloquent\Model;

class VocabGameNew extends Model
{
    protected $fillable = [];
    protected $table = "vocab_games_new";

    public function scores()
    {
        return $this->hasMany("VocabScoreNew", "game_id", "id")->orderBy("card", "ASC");
    }
}