<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aircall_contact_id',
		'first_name',
		'last_name',
		'company_name',
		'information',
		'phone_numbers',
		'emails',
    ];

    public function emails()
    {
        return $this->hasMany('Email', 'contact', 'aircall_contact_id');
    }

    public function phone_numbers()
    {
        return $this->hasMany('PhoneNumber', 'contact', 'aircall_contact_id');
    }
}
