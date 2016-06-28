<?php

use Illuminate\Database\Eloquent\Model;

/**
 * CardSortScore
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $game_id
 * @property integer $level
 * @property integer $card
 * @property integer $value
 * @property string $response
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereCard($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortScore whereResponse($value)
 */
class CardSortScore extends Model {

	protected $fillable = [];
    protected $table = "cardsort_scores";
}