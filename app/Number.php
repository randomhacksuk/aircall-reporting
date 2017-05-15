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
        'aircall_number_id',
		'name',
		'digits',
		'country',
		'time_zone',
		'open',
		'availability_status'
    ];

    public function users()
    {
        return $this->belongsToMany('App\Number', 'user_numbers', 'aircall_user_id', 'aircall_number_id');
    }
}
