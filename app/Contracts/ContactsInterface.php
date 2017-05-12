<?php 

namespace App\Contracts;
    
interface ContactsInterface
{
    /**
     * Get all contacts
     *
     * @return Collection
     */
    public function getAll();

    /**
      * Add new contact
      *
      * @param array $param
      *
      * @return contact
    */
    public function add($param);

    /**
     * Update contact 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param);

    /**
     * Delete contact
     *
     * @return bool
     */
    public function delete($id);

    /**
     * Get contact by aircall_contact_id
     *
     * @return contact
     */
    public function getOne($id);
}
