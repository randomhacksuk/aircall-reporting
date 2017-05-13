<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Call extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aircall_call_id',
		'user_id',
		'status',
		'direction',
		'started_at',
		'answered_at',
		'ended_at',
		'duration',
		'raw_digits',
		'voicemail',
		'recording',
		'archived',
		'number'
    ];

    /**
     * Set call started_at.
     *
     * @param  string  $value
     * @return void
     */
    public function setStartedAtAttribute($value)
    {
    	if($value) {
    		$this->attributes['started_at'] = Carbon::createFromTimestamp($value);
    	}
    }

    /**
     * Set call answered_at.
     *
     * @param  string  $value
     * @return void
     */
    public function setAnsweredAtAttribute($value)
    {
    	if($value) {
        	$this->attributes['answered_at'] = Carbon::createFromTimestamp($value);
        }
    }

    /**
     * Set call ended_at with.
     *
     * @param  string  $value
     * @return void
     */
    public function setEndedAtAttribute($value)
    {
    	if($value) {
        	$this->attributes['ended_at'] = Carbon::createFromTimestamp($value);
    	}
    }

    public function number()
    {
    	return $this->hasOne('App\Number', 'digits', 'number');
    }
}
