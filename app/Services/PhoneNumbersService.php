<?php

namespace App\Services;
use App\Contracts\PhoneNumbersInterface;
use App\PhoneNumber;
use Auth;

class PhoneNumbersService implements PhoneNumbersInterface
{
    /**
     * Create a new instance of PhoneNumbersService.
     *
     * @return void
     */
    function __construct()
    {
        $this->phonenumber = new PhoneNumber();
    }

    /**
     * Get all calls
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->phonenumber->get();
    }

    /**
      * Add new phonenumber
      *
      * @param array $param
      *
      * @return phonenumber
    */
    public function add($param)
    {
        return $this->phonenumber->create($param);
    }

    /**
     * Update phonenumber 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param)
    {
        return $this->phonenumber->find($id)->update($param);
    }

    /**
     * Delete phonenumber
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->phonenumber->where('aircall_id', $id)->delete();
    }
}