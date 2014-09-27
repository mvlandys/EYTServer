<?php

use Illuminate\Database\Eloquent\Model;

class MrAntScore extends Model
{
    protected $table = "mrant_scores";

    protected $fillable = ["game_id", "level", "part", "value", "responseTime"];
}