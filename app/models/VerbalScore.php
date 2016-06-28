<?php

use Illuminate\Database\Eloquent\Model;

/**
 * VerbalScore
 *
 * @property-read \VerbalGame $game
 * @property integer $id
 * @property integer $game_id
 * @property integer $level
 * @property integer $part
 * @property integer $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\VerbalScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalScore whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalScore wherePart($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalScore whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalScore whereUpdatedAt($value)
 */
class VerbalScore extends Model {

	protected $fillable = [];

    protected $table = "verbal_scores";

    public function game()
    {
        return $this->hasOne("VerbalGame", "id", "game_id");
    }
}