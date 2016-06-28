<?php

use Illuminate\Database\Eloquent\Model;

/**
 * FishSharkScore
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $level
 * @property integer $part
 * @property integer $value
 * @property string $responseTime
 * @property string $blankTime
 * @property integer $is_shark
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore wherePart($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereResponseTime($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereBlankTime($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereIsShark($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkScore whereUpdatedAt($value)
 */
class FishSharkScore extends Model
{
    protected $table = "fishshark_scores";

    protected $fillable = ["game_id", "level", "part", "value", "responseTime", "blankTime", "is_shark"];
}