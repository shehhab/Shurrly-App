<?php

namespace App\Http\Controllers\Api\Seeker\Session;

use Carbon\Carbon;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Models\SessionSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\SessionScheduleRequest;
use App\Http\Resources\Auth\SessionScheduleResource;
use App\Models\Day;

class SessionScheduleController extends Controller
{
    public function __invoke(SessionScheduleRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $seekerId = Auth::id();

            // Get the advisor record based on the provided advisor_id
            $advisor = Advisor::find($validatedData['advisor_id']);

            // Check if the advisor exists and is not the same as the seeker
            if (!$advisor || $advisor->seeker_id == $seekerId) {
                return $this->handleResponse(message: "Invalid advisor selected.", status: false, code: 406);
            }

            // Format the session date
            $sessionDate = Carbon::createFromFormat('d/m/Y', $validatedData['session_date']);



            // Check if the session date is not today or any date in the past
            if ($sessionDate->isToday() || $sessionDate->isPast()) {
                return $this->handleResponse(message: "The session date must be in the future.", code: 406, status: false);
            }

            // Check if the day exists
            $dayName = $sessionDate->format('D');

            // Check if the day exists for the advisor and seeker combination
            $dayAvailability = Day::where('day', 'like', "%$dayName%")
                ->where('advisor_id', $validatedData['advisor_id'])
                ->exists();

            if (!$dayAvailability) {
                return $this->handleResponse(message: "This day is not available for the advisor.", code: 406, status: false);
            }

            // Check if the start time is one of the available times
            $start_time = date('H:i', strtotime($validatedData['start_time'])); // Corrected time format

            // Fetch available times for the advisor
            $availableTimes = Day::where('advisor_id', $validatedData['advisor_id'])
                ->where('day', 'like', "%$dayName%")
                ->get(['from', 'to', 'break_from', 'break_to']);

            // Check if the start time falls within available slots
            $isTimeAvailable = false;
            foreach ($availableTimes as $time) {
                $startTime = strtotime($time->from);
                $endTime = strtotime($time->to);

                // Check if the start time is within the range and not during break
                if (strtotime($start_time) >= $startTime && strtotime($start_time) < $endTime) {
                    if (!empty($time->break_from) && strtotime($start_time) < strtotime($time->break_from) || strtotime($start_time) > strtotime($time->break_to)) {
                        $isTimeAvailable = true;
                        break;
                    }
                }
            }

            if (!$isTimeAvailable) {
                return $this->handleResponse(message: "The selected start time is not available.", code: 406, status: false);
            }


            // Create or update the session schedule
            $session =  SessionSchedule::updateOrCreate(
                [
                    'seeker_id' => $seekerId,
                    'advisor_id' => $validatedData['advisor_id'],
                    'advisor_approved' =>  $validatedData['advisor_approved'] = 'Pennding'
                ],
                [

                    'session_date' => $sessionDate->format('Y-m-d'),
                    'start_time' => $start_time,
                    'note' => $validatedData['note'] ?? null,
                ]
            );

            return $this->handleResponse(data: new SessionScheduleResource($session), code: 200);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage() ?: 'An error occurred.';
            return $this->handleResponse(message: $errorMessage, code: 500, status: false);
        }
    }


}
