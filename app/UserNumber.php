<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNumber extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aircall_number_id',
    	'aircall_user_id',
    ];
}
