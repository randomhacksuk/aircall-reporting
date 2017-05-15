<?php 

namespace App\Contracts;
    
interface EmailsInterface
{
    /**
     * Get all emails
     *
     * @return Collection
     */
    public function getAll();

    /**
      * Add new email
      *
      * @param array $param
      *
      * @return email
    */
    public function add($param);

    /**
     * Update email 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param);

    /**
     * Delete email
     *
     * @return bool
     */
    public function delete($id);

    /**
     * Get email by aircallContactId and value
     *
     * @return email
     */
    public function getOne($aircallContactId, $value);
}
