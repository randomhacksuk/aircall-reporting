<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\UsersInterface;
use Artisan;
use App\Log;

class AircallController extends Controller
{
    /**
    * Create a new instance of AircallController class.
    *
    * @return void
    */
	public function __construct(UsersInterface $usersRepo)
	{
        ini_set('max_execution_time', 0);
        set_time_limit(0);
		$this->usersRepo = $usersRepo;
	}

    /**
    * Create a new instance of AircallController class.
    *
    * @return void
    */

    public function getOldData()
    {
        try {
            Artisan::call('old_aircall_users');
        } catch(Exception $e) {
            return response()->back("Something went wrong!");
        }

        try {
            Artisan::call('old_aircall_numbers');
        } catch(Exception $e) {
            return response()->back("Something went wrong!");
        }

        try {
            Artisan::call('old_aircall_contacts');
        } catch(Exception $e) {
            return response()->back("Something went wrong!");
        }

        try {
            Artisan::call('old_aircall_calls');
        } catch(Exception $e) {
            return response()->back("Something went wrong!");
        }

        return response()->json(['result' => 'success']);
    }

    public function getImportPage()
    {
        return view('import', ['importPage' => 'importPage']);
    }

    public function getLogs($lastId)
    {
        $logs = Log::where('id', '>', $lastId)->get();
        return response()->json(['logs' => $logs]);
    }
}
