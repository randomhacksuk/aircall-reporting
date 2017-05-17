<?php

return [    
	
	/**
     * App key for aircall api.
     *
     * @var integer
     */
    'air_call_id' => env('Aircall_Id', ''),

    /**
     * The instance of CallsInterface.
     *
     * @var object
     */
    'air_call_key' => env('Aircall_Token', '')

];