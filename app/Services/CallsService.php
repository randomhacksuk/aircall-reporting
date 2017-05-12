<?php

namespace App\Services;
use App\Contracts\CallsInterface;
use App\Call;
use Auth;

class CallsService implements CallsInterface
{
    /**
     * Create a new instance of CallsService.
     *
     * @return void
     */
    function __construct()
    {
        $this->call = new Call();
    }

    /**
     * Get all calls
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->call->get();
    }

    /**
      * Add new call
      *
      * @param array $param
      *
      * @return call
    */
    public function add($param)
    {
        return $this->call->create($param);
    }

    /**
     * Update call 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param)
    {
        return $this->call->find($id)->update($param);
    }

    /**
     * Delete call
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->call->where('aircall_id', $id)->delete();
    }

    /**
     * Get call by aircall_call_id
     *
     * @return call
     */
    public function getOne($id)
    {
        return $this->call->where('aircall_call_id', $id)->first();
    }
}