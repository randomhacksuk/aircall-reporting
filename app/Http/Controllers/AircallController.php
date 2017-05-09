<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Aircall\AircallClient;

class AircallController extends Controller
{
    public function getAircall()
    {
    	$client = new AircallClient('22e6df7296b0dff9e4080909ae82d604', '7a333e7255576eb86f7479b99643f9db');
    	dd($client->users->getUsers());
    }
}
 