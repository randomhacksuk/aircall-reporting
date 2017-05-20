<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\CallsInterface;
use App\Contracts\UsersInterface;
use App\Number;
use App\Call;
use Carbon\Carbon;

class CallsController extends Controller
{
    /**
    * Create a new instance of CallsController class.
    *
    * @return void
    */
	public function __construct(CallsInterface $callsRepo, UsersInterface $usersRepo)
	{
		$this->callsRepo = $callsRepo;
        $this->usersRepo = $usersRepo;
	}

    public function getReportingDetails()
    {
        $now = new Carbon;
        $oldest = Carbon::createFromTimestamp(Call::orderBy('started_at', 'asc')->pluck('started_at')->first());
        $allNumbers = Number::all();

        $numbers = [];
        foreach ($allNumbers as $key => $number) {
           $numbers[$number->id] = $number->name . ' - ' . $number->digits;
        }

        $months = [];
        for ($i = $oldest; $i <= $now; $i->addMonth()) { 
            $months[$i->format('Y-m')] = $i->format('M o');
        }

        $users = $this->usersRepo->getAll();
        $calls = $this->callsRepo->getFiltered($now->year, $now->month, 'all');
        
        $sortedCalls = $this->sortCalls($calls);
        $graphArray = $this->sortForGraph($calls);

        $data = [
            'users' => $users,
            'sortedCalls' => $sortedCalls,
            'months' => $months,
            'numbers' => $numbers,
            'graphArray' => $graphArray,
            'reportsPage' => 'reportsPage'
        ];

        return view('report', $data);
    }


    public function getFilteredReports($date, $number)
    {

        $date = Carbon::createFromFormat('Y-m', $date);
        $users = $this->usersRepo->getFiltered($date->year, $date->month, $number);
        $calls = $this->callsRepo->getFiltered($date->year, $date->month, $number);
        $graphArray = $this->sortForGraph($calls);
        $sortedCalls = $this->sortCalls($calls);

        return response()->json(['sortedCalls' => $sortedCalls, 'graphArray' => $graphArray, 'users' => $users]);
    }

    public function sortCalls($calls)
    {
        $sortedCalls = [];
        for ($i = 1; $i <= 31; $i++) {
            $totalIncoming = 0;
            $totalIncomingTime = 0;
            $totalOutcoming = 0;
            $totalOutcomingTime = 0;
            $totalMissing = 0;
            $totalVoicemails = 0;
            $totalWaitTime = 0;
            $avgWaitTime = 0;
            foreach ($calls as $key => $call) {
                $date = Carbon::createFromTimestamp($call->started_at);
                if ($date->day == $i) {

                    if ($call->answered_at == null && $call->ended_at == null) {
                        $totalWaitTime += $call->duration;
                    } else if($call->answered_at == null) {
                        $totalWaitTime += $call->duration;
                    } else {
                        $totalWaitTime += $call->answered_at - $call->started_at;
                    }

                    if ($call->direction == 'inbound') {
                        $totalIncoming++;
                        if ($call->answered_at != null) {
                            $totalIncomingTime += $call->ended_at - $call->answered_at;
                        }
                    }
                    if ($call->direction == 'outbound') {
                        $totalOutcoming++;
                        if ($call->answered_at != null) {
                            $totalOutcomingTime += $call->ended_at - $call->answered_at;
                        }
                    }
                    if ($call->answered_at == null) {
                        $totalMissing++;
                    }
                    if ($call->voicemail != null) {
                        $totalVoicemails++;
                    }
                }

            }

            if ($totalIncoming) {
                $missedPercentage = ($totalMissing * 100) / $totalIncoming;
                $missedPercentage = number_format($missedPercentage, 2, '.', '');
            } else {
                $missedPercentage = 0;
            }


            if ($totalMissing) {
                $voicePercantage = ($totalVoicemails * 100) / $totalMissing;
                $voicePercantage = number_format($voicePercantage, 2, '.', '');
            } else {
                $voicePercantage = 0;
            }

            if ($totalIncomingTime > 3600) {
                $totalIncomingTime = gmdate("H:i:s", $totalIncomingTime);
            } else {
                $totalIncomingTime = gmdate("i:s", $totalIncomingTime);
            }
            if ($totalOutcomingTime > 3600) {
                $totalOutcomingTime = gmdate("H:i:s", $totalOutcomingTime);
            } else {
                $totalOutcomingTime = gmdate("i:s", $totalOutcomingTime);
            }

            if ($totalIncoming + $totalOutcoming !== 0) {
                $avgWaitTime = round( $totalWaitTime / ($totalIncoming + $totalOutcoming) );
            }

            $sortedCalls['Number of Incoming Calls'][$i] = $totalIncoming;
            $sortedCalls['Number of Missed Calls'][$i] = $totalMissing;
            $sortedCalls['Percentage of Missed Calls'][$i] = $missedPercentage . '%';
            $sortedCalls['Number of Voicemails'][$i] = $totalVoicemails;
            $sortedCalls['Voicemails Leaving Percentage '][$i] = $voicePercantage . '%';
            $sortedCalls['Average Call Wait Time (Secs)'][$i] = $avgWaitTime;
            $sortedCalls['Total Incoming Talk Time'][$i] = $totalIncomingTime;
            $sortedCalls['Number Outgoing Calls'][$i] = $totalOutcoming;
            $sortedCalls['Total Outoming Talk Time'][$i] = $totalOutcomingTime;
        }

        return $sortedCalls;
    }

    public function sortForGraph($calls)
    {
        $graphArray = [];
        for ($i = 1; $i <= 24; $i++) {
            $inboundAnswered = 0;
            $inboundAbandoned = 0;
            $inboundVoicemails = 0;
            $outbound = 0;
            foreach ($calls as $key => $call) {
                $date = Carbon::createFromTimestamp($call->started_at);
                if ($date->hour == $i) {
                    if ($call->direction == 'inbound') {
                        if ($call->answered_at != null) {
                            $inboundAnswered++;
                        }
                        if ($call->answered_at == null && $call->ended_at != null) {
                            $inboundAbandoned++;
                        }
                    }
                    if ($call->direction == 'outbound') {
                        $outbound++;
                    }
                    if ($call->voicemail !== null) {
                        $inboundVoicemails++;
                    }

                }

            }

            $graphArray[0][0] = 'Inbound Answered';
            $graphArray[1][0] = 'Inbound Abondoned';
            $graphArray[2][0] = 'Inbound Voicemail';
            $graphArray[3][0] = 'Outbound';

            $graphArray[0][] = $inboundAnswered;
            $graphArray[1][] = $inboundAbandoned;
            $graphArray[2][] = $inboundVoicemails;
            $graphArray[3][] = $outbound;
        }
        return $graphArray;
    }

    public function getCallsDetails()
    {
        $calls = $this->callsRepo->getAll();
        $data = [
            'callsPage' => 'callsPage',
            'calls' => $calls
        ];
        return view('calls', $data);
    }
}
 