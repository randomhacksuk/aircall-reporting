<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\CallsInterface;
use App\Contracts\UsersInterface;
use App\Call;
use Carbon\Carbon;
use Log;

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
            'graphArray' => $graphArray
        ];

        return view('report', $data);
    }

    public function getFilteredCalls($date, $location)
    {
        $date = Carbon::createFromFormat('Y-m', $date);
        $calls = $this->callsRepo->getFiltered($date->year, $date->month, $location);
        $sortedCalls = $this->sortCalls($calls);
        return response()->json(['sortedCalls' => $sortedCalls]);
    }

    public function getFilteredCallsGraph($date, $location)
    {
        $date = Carbon::createFromFormat('Y-m', $date);
        $calls = $this->callsRepo->getFiltered($date->year, $date->month, $location);
        $graphArray = $this->sortForGraph($calls);
        return response()->json(['graphArray' => $graphArray]);
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

            if ($totalWaitTime > 3600) {
                $totalWaitTime = gmdate("H:i:s", $totalWaitTime);
            } else {
                $totalWaitTime = gmdate("i:s", $totalWaitTime);
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

            $sortedCalls['Number of Incoming Calls'][$i] = $totalIncoming;
            $sortedCalls['Number of Missed Calls'][$i] = $totalMissing;
            $sortedCalls['Percentage of Missed Calls'][$i] = $missedPercentage . '%';
            $sortedCalls['Number of Voicemails'][$i] = $totalVoicemails;
            $sortedCalls['Voicemails Leaving Percentage '][$i] = $voicePercantage . '%';
            $sortedCalls['Average Call Wait List'][$i] = $totalWaitTime;
            // $sortedCalls['Treatment Time'][$i] = 
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

                }

            }

            $graphArray[0][0] = 'Inbound Answered';
            $graphArray[1][0] = 'Inbound Abandoned';
            $graphArray[2][0] = 'Outbound';

            $graphArray[0][] = $inboundAnswered;
            $graphArray[1][] = $inboundAbandoned;
            $graphArray[2][] = $outbound;
        }
        return $graphArray;
    }
}
 