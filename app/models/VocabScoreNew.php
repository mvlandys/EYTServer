<?php

use Illuminate\Database\Eloquent\Model;

class VocabScoreNew extends Model {

	protected $fillable = [];

    protected $table = "vocab_scores_new";

    public function game()
    {
        return $this->hasOne("VocabGameNew", "id", "game_id");
    }
}