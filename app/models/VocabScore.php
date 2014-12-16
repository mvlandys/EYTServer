<?php

use Illuminate\Database\Eloquent\Model;

class VocabScore extends Model {

	protected $fillable = [];

    protected $table = "vocab_scores";

    public function game()
    {
        return $this->hasOne("VocabGame", "id", "game_id");
    }
}