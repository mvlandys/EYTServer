<?php

use \Illuminate\Database\Eloquent\Model;

/**
 * UserPermissions
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $test_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\UserPermissions whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPermissions whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPermissions whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPermissions whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPermissions whereUpdatedAt($value)
 */
class UserPermissions extends Model
{
    protected $fillable = [];

    protected $table = "user_permissions";
}