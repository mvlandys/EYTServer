<?php

use Illuminate\Database\Eloquent\Model;

/**
 * VocabScoreNew
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $game_id
 * @property integer $card
 * @property integer $value
 * @property string $additional
 * @property-read \VocabGameNew $game
 * @method static \Illuminate\Database\Query\Builder|\VocabScoreNew whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScoreNew whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScoreNew whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScoreNew whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScoreNew whereCard($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScoreNew whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScoreNew whereAdditional($value)
 */
class VocabScoreNew extends Model {

	protected $fillable = [];

    protected $table = "vocab_scores_new";

    public function game()
    {
        return $this->hasOne("VocabGameNew", "id", "game_id");
    }
}