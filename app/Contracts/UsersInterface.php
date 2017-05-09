<?php 

namespace App\Contracts;
    
interface UsersInterface
{
    /**
     * Get List of all users.
     *
     * @return Collection
     */
    public function getAll();

    /**
      * Add new user
      *
      * @param array $param
      *
      * @return user
    */
    public function add($param);

    /**
     * Update the information about selected user 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param);

    /**
     * Delete user
     *
     * @return bool
     */
    public function delete($id);
}
