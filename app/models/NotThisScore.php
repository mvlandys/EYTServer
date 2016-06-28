<?php

use Illuminate\Database\Eloquent\Model;

/**
 * NotThisScore
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $set
 * @property integer $rep
 * @property integer $correct
 * @property string $responseTime
 * @property integer $attempted
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereSet($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereRep($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereCorrect($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereResponseTime($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereAttempted($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisScore whereUpdatedAt($value)
 */
class NotThisScore extends Model {

	protected $fillable = [];

    protected $table = "notthis_scores";

}