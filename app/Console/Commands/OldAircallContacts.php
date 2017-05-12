<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\ContactsInterface;
use App\Contracts\EmailsInterface;
use App\Contracts\PhoneNumbersInterface;
use App\lib\Aircall\AircallClient;
use DB;

class OldAircallContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old_aircall_contacts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $client;

    protected $appId;

    protected $appKey;

    protected $contactsRepo;

    protected $emailsRepo;

    protected $phoneNumbersRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ContactsInterface $contactsRepo, EmailsInterface $emailsRepo, PhoneNumbersInterface $phoneNumbersRepo)
    {
        parent::__construct();
        $this->contactsRepo = $contactsRepo;
        $this->emailsRepo = $emailsRepo;
        $this->phoneNumbersRepo = $phoneNumbersRepo;
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
            'per_page' => 50,
        ];
        $contacts = $this->client->contacts->getContactsWithQuery($array);

        if($contacts->meta->total > 0) {

            foreach ($contacts->contacts as $key => $contact) {

                $this->addContact($contact);
                
            }

            if($contacts->meta->count < $contacts->meta->total) {

                $pageCount = $contacts->meta->total/$contacts->meta->count + 1;

                for ($i=2; $i <= (int)$pageCount; $i++) {

                    $array = [
                        'per_page' => 50,
                        'page' => $i
                    ];

                    $contacts = $this->client->contacts->getContactsWithQuery($array);

                    foreach ($contacts->contacts as $key => $contact) {

                        $this->addContact($contact);

                    }
                } 

            }

        }
    }

    public function addContact($contact)
    {
        $contactData = [];

        $contactData['aircall_contact_id'] = $contact->id;
        $contactData['first_name'] = $contact->first_name;
        $contactData['last_name'] = $contact->last_name;
        $contactData['company_name'] = $contact->company_name; 
        $contactData['information'] = $contact->information;



        $createdContact = DB::transaction(function () use ($contact, $contactData) {

            $createdContact = $this->contactsRepo->add($contactData);

            if(count($contact->emails) > 0) {

                foreach ($contact->emails as $key => $email) {

                    $this->addEmail($email, $createdContact->id);

                }

            }

            if(count($contact->phone_numbers) > 0) {

                foreach ($contact->phone_numbers as $key => $phoneNumber) {
                    
                    $this->addPhoneNumber($phoneNumber, $createdContact->id);

                }

            }

            return $createdContact;

        });
    }

    public function addPhoneNumber($phoneNumber, $createdContactIid)
    {
        $phoneNumberData = [];
        $phoneNumberData['phone_number_id'] = $phoneNumber->id;
        $phoneNumberData['contact_id'] = $createdContactIid;
        $phoneNumberData['label'] = $phoneNumber->label;
        $phoneNumberData['value'] = $phoneNumber->value; 

        return $this->phoneNumbersRepo->add($phoneNumberData);
    }

    public function addEmail($email, $createdContactIid)
    {
        $emailData = [];
        $emailData['email_id'] = $email->id;
        $emailData['contact_id'] = $createdContactIid;
        $emailData['label'] = $email->label;
        $emailData['value'] = $email->value; 

        return $this->emailsRepo->add($emailData);
    }
}
