<?php

use Illuminate\Database\Eloquent\Model;

/**
 * NumeracyScore
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $level
 * @property integer $part
 * @property integer $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \NumeracyGame $game
 * @method static \Illuminate\Database\Query\Builder|\NumeracyScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyScore whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyScore wherePart($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyScore whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyScore whereUpdatedAt($value)
 * @property string $response
 * @method static \Illuminate\Database\Query\Builder|\NumbersScore whereResponse($value)
 */
class NumbersScore extends Model {

	protected $fillable = [];

    protected $table = "numbers_scores";

    public function game()
    {
        return $this->hasOne("NumbersGame", "id", "game_id");
    }
}