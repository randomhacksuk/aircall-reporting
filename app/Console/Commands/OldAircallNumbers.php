<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\NumbersInterface;
use App\Contracts\UserNumbersInterface;
use App\lib\Aircall\AircallClient;
use Exeption;
use App\Log;
use DB;

class OldAircallNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old_aircall_numbers';

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
     * The instance of NumbersInterface.
     *
     * @var object
     */
    protected $numbersRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NumbersInterface $numbersRepo)
    {
        parent::__construct();
        $this->numbersRepo = $numbersRepo;
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

        try {
            $numbers = $this->client->numbers->getNumbersWithQuery($array);
        } catch(Exception $e) {
            sleep(60);
        }

        if($numbers->meta->total > 0) {

            foreach ($numbers->numbers as $key => $number) {

                $this->addNumber($number);

            }

            if($numbers->meta->count < $numbers->meta->total) {

                $pageCount = $numbers->meta->total/$numbers->meta->count + 1;

                for ($i=2; $i <= (int)$pageCount; $i++) {

                    $array = [
                        'per_page' => 50,
                        'page' => $i
                    ];

                    try {
                        $numbers = $this->client->numbers->getNumbersWithQuery($array);
                    } catch(Exception $e) {
                        sleep(60);
                    }

                    foreach ($numbers->numbers as $key => $number) {

                        $this->addNumber($number);

                    }
                } 

            }

        }
    }

    /**
    * Attach number
    *
    * @param Collection $number
    * @param array $number
    *
    * @return collectino
    */
    public function addNumber($number)
    {
        $numberData = [];

        $numberData['aircall_number_id'] = $number->id;
        $numberData['name'] = $number->name;
        $numberData['digits'] = $number->digits;
        $numberData['country'] = $number->country;
        $numberData['time_zone'] = $number->time_zone;
        $numberData['open'] = $number->open;
        $numberData['availability_status'] = $number->availability_status;

        try {
            $createdNumber = $this->numbersRepo->add($numberData);
        } catch(\Illuminate\Database\QueryException $e) {
            return false;
        }

        $data = [
            'aircall_id' => $number->id,
            'name' => $number->name,
            'type' => 'number',
            'success' => true
        ];
        if ($createdNumber) {
            Log::create($data);
        } else {
            $data['success'] = false;
            Log::create($data);
        }

        return $createdNumber;

    }

}

