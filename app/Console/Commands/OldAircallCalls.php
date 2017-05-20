<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\lib\Aircall\AircallClient;
use App\Contracts\CallsInterface;

class OldAircallCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old_aircall_calls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The instance of AircallClient.
     *
     * @var object
     */
    protected $client;

    /**
     * App id for aircall api.
     *
     * @var integer
     */
    protected $appId;

    /**
     * App key for aircall api.
     *
     * @var integer
     */
    protected $appKey;

    /**
     * The instance of CallsInterface.
     *
     * @var object
     */
    protected $callsRepo;

    /**
     * Create a new command instance.
     *
     * @param CallsInterface $callsRepo
     *
     * @return void
     */
    public function __construct(CallsInterface $callsRepo)
    {
        parent::__construct();
        $this->callsRepo = $callsRepo;
        $appId = config('aircall.air_call_id');
        $appKey = config('aircall.air_call_key');
        $this->client = new AircallClient($appId, $appKey);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $array = [
            'per_page' => 50,
        ];

        //dd(\App\Call::where('id', 1)->first());

        $calls = $this->client->calls->getCallsWithQuery($array);

        if($calls->meta->total > 0) {

            foreach ($calls->calls as $key => $call) {

                if($call->status == 'done') {

                    $this->addCall($call);
 
                }
                
            }

            if($calls->meta->count < $calls->meta->total) {

                $pageCount = $calls->meta->total/$calls->meta->count + 1;

                for ($i=2; $i <= (int)$pageCount; $i++) {

                    $array = [
                        'per_page' => 50,
                        'page' => $i
                    ];
                    
                    $calls = $this->client->calls->getCallsWithQuery($array);

                    foreach ($calls->calls as $key => $call) {
                        if($call->status == 'done') {

                            $this->addCall($call);

                        }
                    }
                } 

            }

        }
    }

    /**
    * Add call
    * 
    * @param Collection $call
    *
    * @return collection
    */
    public function addCall($call)
    {
        $callData = [];

        if(isset($call->user)) {
            $callData['user_id'] = $call->user->id;
        }

        $callData['aircall_call_id'] = $call->id;
        $callData['number'] = $call->number->digits;
        $callData['direction'] = $call->direction;
        $callData['status'] = $call->status; 
        $callData['started_at'] = $call->started_at;
        $callData['answered_at'] = $call->answered_at;
        $callData['ended_at'] = $call->ended_at;
        $callData['duration'] = $call->duration;
        $callData['voicemail'] = $call->voicemail;
        $callData['recording'] = $call->recording;
        $callData['raw_digits'] = $call->raw_digits;
        $callData['archived'] = $call->archived;

        try {
            return $this->callsRepo->add($callData);
        } catch(\Illuminate\Database\QueryException $e) {
            return false;
        }
        
    }

}
