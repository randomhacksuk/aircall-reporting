<?php 

namespace App\Contracts;
    
interface UserNumbersInterface
{

    /**
      * Add new usernumber
      *
      * @param array $param
      *
      * @return usernumber
    */
    public function add($param);

    /**
     * Get usernumber by aircall_user_id and aircall_number_id
     *
     * @return usernumber
     */
    public function getOne($aircallUserId, $aircallNumberId);
}
