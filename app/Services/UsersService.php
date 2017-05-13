<?php

namespace App\Services;
use App\Contracts\UsersInterface;
use App\User;
use Auth;

class UsersService implements UsersInterface
{
    /**
     * Create a new instance of UsersService
     *
     * @return void
     */
    function __construct()
    {
        $this->user = new User();
    }

    /**
     * Get all users
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->user->with('calls')->get();
    }

    /**
      * Add new user
      *
      * @param array $param
      *
      * @return user
    */
    public function add($param)
    {
        return $this->user->create($param);
    }

    /**
     * Update user 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param)
    {
        return $this->user->find($id)->update($param);
    }

    /**
     * Delete user
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->user->where('aircall_id', $id)->delete();
    }
}