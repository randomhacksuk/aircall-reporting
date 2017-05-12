<?php 

namespace App\Contracts;
    
interface PhoneNumbersInterface
{
    /**
     * Get all phonenumbers
     *
     * @return Collection
     */
    public function getAll();

    /**
      * Add new phonenumber
      *
      * @param array $param
      *
      * @return phonenumber
    */
    public function add($param);

    /**
     * Update phonenumber 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param);

    /**
     * Delete phonenumber
     *
     * @return bool
     */
    public function delete($id);
}
