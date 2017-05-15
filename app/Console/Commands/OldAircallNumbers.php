<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\NumbersInterface;
use App\Contracts\UserNumbersInterface;
use App\lib\Aircall\AircallClient;
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

    protected $client;

    protected $appId;

    protected $appKey;

    protected $numbersRepo;

    protected $userNumbersRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NumbersInterface $numbersRepo, UserNumbersInterface $userNumbersRepo)
    {
        parent::__construct();
        $this->numbersRepo = $numbersRepo;
        $this->userNumbersRepo = $userNumbersRepo;
        $appId = config('app.air_call_id');
        $appKey = config('app.air_call_key');
        $this->client = new AircallClient($appId, $appKey);
    }

        /**
     * Execute the console command.
     *
     * @return mixed
     */
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

        $numbers = $this->client->numbers->getNumbersWithQuery($array);

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

                    $numbers = $this->client->numbers->getNumbersWithQuery($array);

                    foreach ($numbers->numbers as $key => $number) {

                        $this->addNumber($number);

                    }
                } 

            }

        }
    }

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

        $createdNumber = DB::transaction(function () use ($number, $numberData) {
            $createdNumber = $this->numbersRepo->add($numberData);

            if((isset($number->users) && count($number->users) > 0)) {
                foreach ($number->users as $key => $user) {

                    $this->addUserNumber($createdNumber, $user);

                }

            }

            return $createdNumber;

        });

    }

    public function addUserNumber($createdNumber, $user)
    {
        if(!$this->userNumbersRepo->getOne($user->id, $createdNumber->aircall_number_id)) {
            return $createdNumber->users()->attach($user->id);
        }
    }

}

