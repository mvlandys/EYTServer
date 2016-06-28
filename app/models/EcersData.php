<?php

use Illuminate\Database\Eloquent\Model;

/**
 * EcersData
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $entry_id
 * @property string $test
 * @property integer $page
 * @property integer $item
 * @property integer $item_num
 * @property integer $value
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereEntryId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereTest($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData wherePage($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereItem($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereItemNum($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersData whereValue($value)
 */
class EcersData extends Model {

	protected $fillable = [];
    protected $table = "ecers_data";
}