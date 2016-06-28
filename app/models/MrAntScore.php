<?php

use Illuminate\Database\Eloquent\Model;

/**
 * MrAntScore
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $level
 * @property integer $part
 * @property integer $value
 * @property string $responseTime
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore wherePart($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore whereResponseTime($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntScore whereUpdatedAt($value)
 */
class MrAntScore extends Model
{
    protected $table = "mrant_scores";

    protected $fillable = ["game_id", "level", "part", "value", "responseTime"];
}