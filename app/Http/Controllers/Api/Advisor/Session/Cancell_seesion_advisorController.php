<?php

namespace App\Http\Controllers\Api\Advisor\Session;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SessionSchedule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advisor\Session\AcceptRequest;

class Cancell_seesion_advisorController extends Controller
{
    public function __invoke(AcceptRequest $request){
        try {
            $validatedData = $request->validated();

            // Retrieve the session to be updated or return error if not found
            $session = SessionSchedule::find($validatedData['session_id']);
            if (!$session) {
                return $this->handleResponse(message: 'Session not found.',code :  404 , status :false);
            }

            // Check if the session belongs to the advisor
            if ($session->advisor_id !== auth()->id()) {
                return $this->handleResponse(message: 'Unauthorized action.',code : 403 , status :false);
            }

            // Check if the session has already been approved by the advisor
            if ($session->advisor_approved === "Accept") {
                return $this->handleResponse(message: "Can't Edite Becouse Session already approved ",code: 400 , status :false);
            }

            // Check if the session has already been approved by the advisor
            if ($session->advisor_approved === "Not_Accept") {
                return $this->handleResponse(message: 'Session already Canceel.',code: 400 , status :false);
            }
            // If the session date is not at least one day ahead, return an error
            if (Carbon::parse($session->session_date)->isToday() ||Carbon::parse($session->session_date)->isPast() ) {
                return $this->handleResponse(message: 'Session date should be at least one day ahead of today.', code: 400, status: false);
            }
            // Update the value
            $session->advisor_approved = "Not_Accept";

            // Save the changes
            $session->save();

            // Return the appropriate response
            return $this->handleResponse(message: 'Session Canceel successfully');
        } catch (\Exception $e) {
            return $this->handleResponse(message:  $e->getMessage(),code : 500 , status :false );
        }
    }
}
