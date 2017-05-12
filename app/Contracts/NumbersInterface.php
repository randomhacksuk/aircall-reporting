<?php 

namespace App\Contracts;
    
interface NumbersInterface
{
    /**
     * Get all numbers
     *
     * @return Collection
     */
    public function getAll();

    /**
      * Add new number
      *
      * @param array $param
      *
      * @return number
    */
    public function add($param);

    /**
     * Update number 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param);

    /**
     * Delete number
     *
     * @return bool
     */
    public function delete($id);
}
