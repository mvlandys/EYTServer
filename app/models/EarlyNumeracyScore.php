<?php

use Illuminate\Database\Eloquent\Model;


/**
 * EarlyNumeracyScore
 *
 * @property integer $id
 * @property integer $game_id
 * @property string $item
 * @property integer $value
 * @property string $response
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \EarlyNumeracyGame $game
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyScore whereItem($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyScore whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyScore whereResponse($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyScore whereUpdatedAt($value)
 */
class EarlyNumeracyScore extends Model {

	protected $fillable = [];

    protected $table = "early_numeracy_scores";

    public function game()
    {
        return $this->hasOne("EarlyNumeracyGame", "id", "game_id");
    }
}