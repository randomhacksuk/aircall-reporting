<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\CallsInterface;
use Carbon\Carbon;
use Log;

class CallsController extends Controller
{
    /**
    * Create a new instance of CallsController class.
    *
    * @return void
    */
	public function __construct(CallsInterface $callsRepo)
	{
		$this->callsRepo = $callsRepo;
	}

    public function postAircallCalls(Request $request)
    {
    	$data = $request->all();
    	$callData = [];
    	Log::info($data);
    	if($data['resource'] == 'call') {
    		if($data['event'] == 'call.created') {
    			$callData['user_id'] = $data['data']['user']['id'];
    			$callData['aircall_call_id'] = $data['data']['id'];
    			$callData['number'] = $data['data']['number']['digits'];
    			$callData['direction'] = $data['data']['direction'];
    			$callData['status'] = $data['data']['status'];
    			$callData['started_at'] = $data['data']['started_at'];
    			$callData['answered_at'] = $data['data']['answered_at'];
    			$callData['ended_at'] = $data['data']['ended_at'];
    			$callData['duration'] = $data['data']['duration'];
    			$callData['voicemail'] = $data['data']['voicemail'];
    			$callData['recording'] = $data['data']['recording'];
    			$callData['raw_digits'] = $data['data']['raw_digits'];
    			$callData['archived'] = $data['data']['archived'];
    			if($this->callsRepo->add($callData)) {
    				return;
    			}
    		} elseif($data['event'] == 'call.deleted') {
    			if($this->callsRepo->delete($data['data']['id'])) {
    				return;
    			}
    		}
    	}
    }
}

// [2017-05-10 13:11:32] local.INFO: array (
//   'resource' => 'call',
//   'event' => 'call.created',
//   'timestamp' => 1494421891,
//   'token' => '78f898d1d4b9c9015064b3d72a1bdedd',
//   'data' =>
//   array (
//     'id' => 21845642,
//     'direct_link' => 'https://api.aircall.io/v1/calls/21845642',
//     'direction' => 'outbound',
//     'status' => 'initial',
//     'started_at' => 1494421891,
//     'answered_at' => NULL,
//     'ended_at' => NULL,
//     'duration' => '0',
//     'voicemail' => NULL,
//     'recording' => NULL,
//     'raw_digits' => '+374 98 968698',
//     'user' =>
//     array (
//       'id' => 157227,
//       'direct_link' => 'https://api.aircall.io/v1/users/157227',
//       'name' => 'David Martirosyan',
//       'email' => 'david1994martirosyan@gmail.com',
//       'available' => true,
//       'availability_status' => 'custom',
//     ),
//     'number' =>
//     array (
//       'id' => 37389,
//       'direct_link' => 'https://api.aircall.io/v1/numbers/37389',
//       'name' => 'Second Number',
//       'digits' => '+44 113 868 2333',
//       'country' => 'GB',
//       'time_zone' => 'Europe/Paris',
//       'open' => true,
//       'availability_status' => 'open',
//     ),
//     'archived' => false,
//     'comments' =>
//     array (
//     ),
//     'tags' =>
//     array (
//     ),
//   ),
// )
// [2017-05-10 13:11:54] local.INFO: array (
//   'resource' => 'call',
//   'event' => 'call.answered',
//   'timestamp' => 1494421914,
//   'token' => '78f898d1d4b9c9015064b3d72a1bdedd',
//   'data' =>
//   array (
//     'id' => 21845642,
//     'direct_link' => 'https://api.aircall.io/v1/calls/21845642',
//     'direction' => 'outbound',
//     'status' => 'answered',
//     'started_at' => 1494421891,
//     'answered_at' => 1494421914,
//     'ended_at' => NULL,
//     'duration' => '0',
//     'voicemail' => NULL,
//     'recording' => NULL,
//     'raw_digits' => '+374 98 968698',
//     'user' =>
//     array (
//       'id' => 157227,
//       'direct_link' => 'https://api.aircall.io/v1/users/157227',
//       'name' => 'David Martirosyan',
//       'email' => 'david1994martirosyan@gmail.com',
//       'available' => true,
//       'availability_status' => 'custom',
//     ),
//     'number' =>
//     array (
//       'id' => 37389,
//       'direct_link' => 'https://api.aircall.io/v1/numbers/37389',
//       'name' => 'Second Number',

