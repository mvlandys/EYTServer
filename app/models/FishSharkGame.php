<?php

use Illuminate\Database\Eloquent\Model;

/**
 * FishSharkGame
 *
 * @property integer $id
 * @property string $subject_id
 * @property string $session_id
 * @property string $test_name
 * @property string $grade
 * @property string $dob
 * @property string $age
 * @property string $sex
 * @property string $played_at
 * @property string $animation
 * @property string $blank_min
 * @property string $blank_max
 * @property string $ts_start
 * @property string $ts_lvl1_start
 * @property string $ts_lvl1_end
 * @property string $ts_lvl2_start
 * @property string $ts_lvl2_end
 * @property string $ts_lvl3_start
 * @property string $ts_lvl3_end
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\FishSharkScore[] $scores
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereAnimation($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereBlankMin($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereBlankMax($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTsStart($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTsLvl1Start($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTsLvl1End($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTsLvl2Start($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTsLvl2End($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTsLvl3Start($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereTsLvl3End($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\FishSharkGame whereUpdatedAt($value)
 */
class FishSharkGame extends Model
{
    protected $table = "fishshark_games";
    protected $fillable = ["subject_id", "session_id", "test_name", "grade", "dob", "age", "sex", "played_at",
        "animation", "blank_min", "blank_max", "ts_start", "ts_lvl1_start", "ts_lvl1_end", "ts_lvl2_start",
        "ts_lvl2_end", "ts_lvl3_start", "ts_lvl3_end"
    ];

    public function scores()
    {
        return $this->hasMany("FishSharkScore", "game_id", "id");
    }
}