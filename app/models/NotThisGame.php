<?php

use Illuminate\Database\Eloquent\Model;

class NotThisGame extends Model
{
    protected $fillable = [];
    protected $table = "notthis_games";

    public function scores()
    {
        return $this->hasMany("NotThisScore", "game_id", "id");
    }
}