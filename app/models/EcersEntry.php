<?php

use Illuminate\Database\Eloquent\Model;

class EcersEntry extends Model {

	protected $fillable = [];
    protected $table = "ecers_entries";

    public function data()
    {
        return $this->hasMany("EcersData", "entry_id", "id");
    }
}