<?php

namespace App\Services;
use App\Contracts\ContactsInterface;
use App\Contact;
use Auth;

class ContactsService implements ContactsInterface
{
    /**
     * Create a new instance of ContactsService.
     *
     * @return void
     */
    function __construct()
    {
        $this->contact = new Contact();
    }

    /**
     * Get all contacts
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->contact->get();
    }

    /**
      * Add new contact
      *
      * @param array $param
      *
      * @return contact
    */
    public function add($param)
    {
        return $this->contact->create($param);
    }

    /**
     * Update contact 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param)
    {
        return $this->contact->find($id)->update($param);
    }

    /**
     * Delete contact
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->contact->where('aircontact_id', $id)->delete();
    }

    /**
     * Get contact by aircall_contact_id
     *
     * @return contact
     */
    public function getOne($id)
    {
        return $this->contact->where('aircall_contact_id', $id)->first();
    }
}