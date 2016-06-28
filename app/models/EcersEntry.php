<?php

use Illuminate\Database\Eloquent\Model;

/**
 * EcersEntry
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $centre
 * @property string $room
 * @property string $observer
 * @property string $study
 * @property string $start
 * @property string $end
 * @property-read \Illuminate\Database\Eloquent\Collection|\EcersData[] $data
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereCentre($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereRoom($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereObserver($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereStudy($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersEntry whereEnd($value)
 */
class EcersEntry extends Model {

	protected $fillable = [];
    protected $table = "ecers_entries";

    public function data()
    {
        return $this->hasMany("EcersData", "entry_id", "id");
    }
}