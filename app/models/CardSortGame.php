<?php

use Illuminate\Database\Eloquent\Model;

class CardSortGame extends Model {

	protected $fillable = [];
    protected $table = "cardsort_games";

    public function scores()
    {
        return $this->hasMany("CardSortScore", "game_id", "id");
    }
}