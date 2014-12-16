<?php

use Illuminate\Database\Eloquent\Model;

class VocabGame extends Model
{
    protected $fillable = [];
    protected $table = "vocab_games";

    public function scores()
    {
        return $this->hasMany("VocabScore", "game_id", "id")->orderBy("card", "ASC");
    }
}