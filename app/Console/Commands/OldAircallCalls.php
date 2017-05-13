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

    protected $client;

    protected $appId;

    protected $appKey;

    protected $callsRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CallsInterface $callsRepo)
    {
        parent::__construct();
        $this->callsRepo = $callsRepo;
        $appId = config('app.air_call_id');
        $appKey = config('app.air_call_key');
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
            'per_page' => 5,
            'order' => 'desc'
        ];

        $calls = $this->client->calls->getCallsWithQuery($array);
        dd($calls);

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

        return $this->callsRepo->add($callData);
    }

}
