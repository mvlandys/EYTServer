<?php

use Illuminate\Database\Eloquent\Model;

/**
 * VocabScore
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $game_id
 * @property integer $card
 * @property integer $value
 * @property string $additional
 * @property-read \VocabGame $game
 * @method static \Illuminate\Database\Query\Builder|\VocabScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScore whereCard($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScore whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabScore whereAdditional($value)
 */
class VocabScore extends Model {

	protected $fillable = [];

    protected $table = "vocab_scores";

    public function game()
    {
        return $this->hasOne("VocabGame", "id", "game_id");
    }
}