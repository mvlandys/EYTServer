<?php

use Illuminate\Database\Eloquent\Model;

/**
 * CardSortGame
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
 * @property string $ts_start
 * @property string $ts_lvl1_start
 * @property string $ts_lvl1_end
 * @property string $ts_lvl2_start
 * @property string $ts_lvl2_end
 * @property string $ts_lvl3_start
 * @property string $ts_lvl3_end
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\CardSortScore[] $scores
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTsStart($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTsLvl1Start($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTsLvl1End($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTsLvl2Start($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTsLvl2End($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTsLvl3Start($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereTsLvl3End($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CardSortGame whereUpdatedAt($value)
 */
class CardSortGame extends Model {

	protected $fillable = [];
    protected $table = "cardsort_games";

    public function scores()
    {
        return $this->hasMany("CardSortScore", "game_id", "id");
    }
}