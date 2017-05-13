<?php 

namespace App\Contracts;
    
interface CallsInterface
{
    /**
     * Get all calls
     *
     * @return Collection
     */
    public function getAll();

    /**
      * Add new call
      *
      * @param array $param
      *
      * @return call
    */
    public function add($param);

    /**
     * Update call 
     * 
     * @param integer $id
     * @param array $param 
     *
     * @return bool
    */
    public function update($id, $param);

    /**
     * Delete call
     *
     * @return bool
     */
    public function delete($id);

    /**
     * Get call by aircall_call_id
     *
     * @return call
     */
    public function getOne($id);

    /**
     * Get last month calls
     *
     * @return call
     */
    public function getFiltered($year, $month, $location);
}
