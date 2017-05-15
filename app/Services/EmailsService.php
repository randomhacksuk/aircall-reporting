<?php

namespace App\Services;
use App\Contracts\EmailsInterface;
use App\Email;
use Auth;

class EmailsService implements EmailsInterface
{
    /**
     * Create a new instance of EmailsService.
     *
     * @return void
     */
    function __construct()
    {
        $this->email = new Email();
    }

    /**
     * Get all emails
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->email->get();
    }

    /**
      * Add new email
      *
      * @param array $param
      *
      * @return email
    */
    public function add($param)
    {
        return $this->email->create($param);
    }

    /**
     * Update email 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param)
    {
        return $this->email->find($id)->update($param);
    }

    /**
     * Delete email
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->email->where('aircall_id', $id)->delete();
    }

    /**
     * Get email by aircallContactId and value
     *
     * @return email
     */
    public function getOne($aircallContactId, $value)
    {
        return $this->email->where('contact_id', $aircallContactId)->where('value', $value)->first();
    }
}