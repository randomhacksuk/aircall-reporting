<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\UsersInterface;
use Artisan;

use Log;

class AircallController extends Controller
{
    /**
    * Create a new instance of AircallController class.
    *
    * @return void
    */
	public function __construct(UsersInterface $usersRepo)
	{
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

        return redirect()->action('CallsController@getReportingDetails');
    }
}
