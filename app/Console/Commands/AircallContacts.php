<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\ContactsInterface;
use App\Contracts\EmailsInterface;
use App\Contracts\PhoneNumbersInterface;
use App\lib\Aircall\AircallClient;
use Carbon\Carbon;
use DB;

class AircallContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aircall_contacts';

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
     * The instance of ContactsInterface.
     *
     * @var object
     */
    protected $contactsRepo;

    /**
     * The instance of EmailsInterface.
     *
     * @var object
     */
    protected $emailsRepo;

    /**
     * The instance of PhoneNumbersInterface.
     *
     * @var object
     */
    protected $phoneNumbersRepo;

    /**
     * Create a new command instance.
     *
     * @param ContactsInterface $contactsRepo
     * @param EmailsInterface $emailsRepo
     * @param PhoneNumbersInterface $phoneNumbersRepo
     *
     * @return void
     */
    public function __construct(ContactsInterface $contactsRepo, EmailsInterface $emailsRepo, PhoneNumbersInterface $phoneNumbersRepo)
    {
        parent::__construct();
        $this->contactsRepo = $contactsRepo;
        $this->emailsRepo = $emailsRepo;
        $this->phoneNumbersRepo = $phoneNumbersRepo;
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

                    $this->addEmail($email, $createdContact);

                }

            }

            if(count($contact->phone_numbers) > 0) {

                foreach ($contact->phone_numbers as $key => $phoneNumber) {
                    
                    $this->addPhoneNumber($phoneNumber, $createdContact);

                }

            }

            return $createdContact;

        });
    }

    /**
    * Add email
    * 
    * @param Collection $email
    * @param Collection $createdContact
    *
    * @return collection
    */
    public function addEmail($email, $createdContact)
    {
        if(!$this->emailsRepo->getOne($createdContact->aircall_contact_id, $email->value)) {

            $emailData = [];
            $emailData['email_id'] = $email->id;
            $emailData['contact_id'] = $createdContact->aircall_contact_id;
            $emailData['label'] = $email->label;
            $emailData['value'] = $email->value;

            return $this->emailsRepo->add($emailData);

        }
        return;
    }

    /**
    * Add phone number
    * 
    * @param Collection $phoneNumber
    * @param Collection $createdContact
    *
    * @return collection
    */
    public function addPhoneNumber($phoneNumber, $createdContact)
    {
        if(!$this->phoneNumbersRepo->getOne($createdContact->aircall_contact_id, $phoneNumber->value)) {

            $phoneNumberData = [];
            $phoneNumberData['phone_number_id'] = $phoneNumber->id;
            $phoneNumberData['contact_id'] = $createdContact->aircall_contact_id;
            $phoneNumberData['label'] = $phoneNumber->label;
            $phoneNumberData['value'] = $phoneNumber->value;

            return $this->phoneNumbersRepo->add($phoneNumberData);

        }
        return;
    }
}
