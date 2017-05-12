<?php

namespace App\Services;
use App\Contracts\UserNumbersInterface;
use App\UserNumber;
use Auth;

class UserNumbersService implements UserNumbersInterface
{
    /**
     * Create a new instance of CallsService.
     *
     * @return void
     */
    function __construct()
    {
        $this->usernumber = new UserNumber();
    }

    /**
      * Add new usernumber
      *
      * @param array $param
      *
      * @return usernumber
    */
    public function add($param)
    {
        return $this->usernumber->create($param);
    }

    /**
     * Get usernumber by aircall_user_id and aircall_number_id
     *
     * @return usernumber
     */
    public function getOne($aircallUserId, $aircallNumberId)
    {
        return $this->usernumber->where('aircall_user_id', $aircallUserId)->where('aircall_number_id', $aircallNumberId)->first();
    }
}