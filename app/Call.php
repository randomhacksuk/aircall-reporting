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
     * Set call ended_at.
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

    /**
     * Get call started_at.
     *
     * @param  string  $value
     * @return string
     */
    public function getStartedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->timestamp;
        }
    }

    /**
     * Get call answered_at.
     *
     * @param  string  $value
     * @return string
     */
    public function getAnsweredAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->timestamp;
        }
    }

    /**
     * Get call ended_at.
     *
     * @param  string  $value
     * @return string
     */
    public function getEndedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->timestamp;
        }
    }

    public function number()
    {
        return $this->hasOne('App\Number', 'digits', 'number');
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'aircall_user_id');
    }
}
