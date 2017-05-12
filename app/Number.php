<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name',
		'digits',
		'country',
		'time_zone',
		'open',
		'availability_status'
    ];

    public function users()
    {
        return $this->belongsToMany('App\Number', 'user_numbers', 'number_id', 'user_id');
    }
}
