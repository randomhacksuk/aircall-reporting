<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\UsersInterface;
use App\lib\Aircall\AircallClient;
use DB;

class OldAircallUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old_aircall_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $client;

    protected $appId;

    protected $appKey;

    protected $usersRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UsersInterface $usersRepo)
    {
        parent::__construct();
        $this->usersRepo = $usersRepo;
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

        $users = $this->client->users->getUsersWithQuery($array);

        if($users->meta->total > 0) {

            foreach ($users->users as $key => $user) {

                $this->addUser($user);

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

                        $this->addUser($user);

                    }
                } 

            }

        }
    }

    public function addUser($user)
    {
        $userData = [];

        if(isset($user->user)) {
            $userData['user_id'] = $user->user->id;
        }

        $userData['aircall_user_id'] = $user->id;
        $userData['name'] = $user->name;
        $userData['email'] = $user->email;
        $userData['available'] = $user->available; 
        $userData['availability_status'] = $user->availability_status;

        return $this->usersRepo->add($userData);
    }
}
