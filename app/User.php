<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aircall_user_id',
        'name',
        'email',
        'available',
        'availability_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function setPrimaryKey($key)
    {
      $this->primaryKey = $key;
    }

    public function numbers()
    {
        $this->setPrimaryKey('aircall_user_id');
        return $this->belongsToMany('\App\Number', 'user_numbers', 'aircall_user_id', 'aircall_number_id');
    }

    public function calls()
    {
        return $this->hasMany('\App\Call', 'user_id', 'aircall_user_id');
    }
}
