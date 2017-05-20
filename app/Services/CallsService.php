<?php

namespace App\Services;
use App\Contracts\CallsInterface;
use App\Call;
use Carbon\Carbon;
use Auth;
use DB;

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
        return $this->call->with(['number', 'user'])->get();
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

    /**
     * Get last month calls
     *
     * @return call
     */
    public function getFiltered($year, $month, $number)
    {        
        return $this->call
            ->whereYear('started_at', $year)
            ->whereMonth('started_at', $month)
            ->whereHas('number', function($query) use ($number)
                {
                    if ($number == 'all') {

                    } else {
                        $query->where('id', $number);
                    }
                }
            )->get();
    }
}