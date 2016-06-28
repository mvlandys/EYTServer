<?php

use \Illuminate\Database\Eloquent\Model;

/**
 * AppUser
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\AppUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\AppUser whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\AppUser wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\AppUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\AppUser whereUpdatedAt($value)
 */
class AppUser extends Model
{
    protected $table = "app_users";
}