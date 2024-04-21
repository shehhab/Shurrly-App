<?php

namespace App\Http\Controllers\Api\Advisor\Session;

use Illuminate\Http\Request;
use App\Models\SessionSchedule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advisor\Session\AcceptRequest;

class HideSeesionHistoryController extends Controller
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
            if ($session->advisor_history === 0) {
                return $this->handleResponse(message: "Can't Edite Becouse Session already Deleted From History ",code: 400 , status :false);
            }



            // Update the value
            $session->advisor_history = 0;

            // Save the changes
            $session->save();

            // Return the appropriate response
            return $this->handleResponse(message: 'Session Hide successfully');
        } catch (\Exception $e) {
            return $this->handleResponse(message:  $e->getMessage(),code : 500 , status :false );
        }
    }
}
