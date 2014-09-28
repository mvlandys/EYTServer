<?php

use Illuminate\Database\Eloquent\Model;

class FishSharkScore extends Model
{
    protected $table = "fishshark_scores";

    protected $fillable = ["game_id", "level", "part", "value", "responseTime", "blankTime", "is_shark"];
}