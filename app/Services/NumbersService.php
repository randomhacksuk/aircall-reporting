<?php

namespace App\Services;
use App\Contracts\NumbersInterface;
use App\Number;
use Auth;

class NumbersService implements NumbersInterface
{
    /**
     * Create a new instance of NumbersService
     *
     * @return void
     */
    function __construct()
    {
        $this->number = new Number();
    }

    /**
     * Get all numbers
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->number->get();
    }

    /**
      * Add new number
      *
      * @param array $param
      *
      * @return user
    */
    public function add($param)
    {
        return $this->number->create($param);
    }

    /**
     * Update number 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param)
    {
        return $this->number->find($id)->update($param);
    }

    /**
     * Delete number
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->number->where('aircall_id', $id)->delete();
    }
}