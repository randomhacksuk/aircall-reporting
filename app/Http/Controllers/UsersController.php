<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\UsersInterface;

use Log;

class UsersController extends Controller
{
    /**
    * Create a new instance of UsersController class.
    *
    * @return void
    */
	public function __construct(UsersInterface $usersRepo)
	{
		$this->usersRepo = $usersRepo;
	}

    public function postAircallUsers(Request $request)
    {
    	$data = $request->all();
    	$userData = [];
    	if($data['resource'] == 'user') {
    		if($data['event'] == 'user.created') {
    			$userData['aircall_user_id'] = $data['data']['id'];
    			$userData['name'] = $data['data']['name'];
    			$userData['email'] = $data['data']['email'];
    			$userData['available'] = $data['data']['available'];
    			$userData['availability_status'] = $data['data']['availability_status'];
    			if($this->usersRepo->add($userData)) {
    				return;
    			}
    		} elseif($data['event'] == 'user.deleted') {
    			if($this->usersRepo->delete($data['data']['id'])) {
    				return;
    			}
    		}
    	}
    }
}
