<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\UsersInterface;
use App\Contracts\UserNumbersInterface;
use App\lib\Aircall\AircallClient;
use Carbon\Carbon;
use DB;

class AircallUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aircall_users';

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
     * The instance of UsersInterface.
     *
     * @var object
     */
    protected $usersRepo;

    /**
     * The instance of UserNumbersInterface.
     *
     * @var object
     */
    protected $userNumbersRepo;


    /**
     * Create a new command instance.
     *
     * @param UsersInterface $usersRepo
     * @param UserNumbersInterface $userNumbersRepo
     *
     * @return void
     */
    public function __construct(UsersInterface $usersRepo, UserNumbersInterface $userNumbersRepo)
    {
        parent::__construct();
        $this->usersRepo = $usersRepo;
        $this->userNumbersRepo = $userNumbersRepo;
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
    	$from = Carbon::now()->subHours(24)->timestamp;
        $to = Carbon::now()->timestamp;
        $array = [
            'from' => $from,
            'to' => $to,
            'per_page' => 50,
        ];

        $users = $this->client->users->getUsersWithQuery($array);

        if($users->meta->total > 0) {

            foreach ($users->users as $key => $user) {

                try {
                    $this->addUser($user);
                } catch(Exception $e) {
                    continue;
                }


            }

            if($users->meta->count < $users->meta->total) {

                $pageCount = $users->meta->total/$users->meta->count + 1;

                for ($i=2; $i <= (int)$pageCount; $i++) {

                    $array = [
                        'per_page' => 50,
                        'page' => $i
                    ];

                    $users = $this->client->users->getUsersWithQuery($array);

                    foreach ($users->users as $key => $user) {

                        try {
                            $this->addUser($user);
                        } catch(Exception $e) {
                            continue;
                        }

                    }
                } 

            }

        }
    }

    public function addUser($user)
    {
        $userData = [];

        $userData['aircall_user_id'] = $user->id;
        $userData['name'] = $user->name;
        $userData['email'] = $user->email;
        $userData['available'] = $user->available;
        $userData['availability_status'] = $user->availability_status;

        try {
            $createdUser = $this->usersRepo->add($userData);
        } catch(\Illuminate\Database\QueryException $e) {
            return false;
        }

        if($createdUser) {

            $this->getUser($createdUser->aircall_user_id, $createdUser);

        }

    }

    /**
    * Get user info from aicall
    *
    * @param Collection $user
    *
    * @return addUserNumber
    */
    public function getUser($userId, $createdUser)
    {
        $user = $this->client->users->getuser($userId);

        if($user->user->numbers && count($user->user->numbers) > 0) {

            foreach ($user->user->numbers as $key => $number) {

                $this->addUserNumber($createdUser, $number);

            }
        }

    }

    /**
    * Attach number to user
    *
    * @param Collection $createdUser
    * @param array $number
    *
    * @return boolean
    */
    public function addUserNumber($createdUser, $number)
    {
        if(!$this->userNumbersRepo->getOne($createdUser->aircall_user_id, $number->id)) {

            return $createdUser->numbers()->attach($number->id);

        }
    }
}
