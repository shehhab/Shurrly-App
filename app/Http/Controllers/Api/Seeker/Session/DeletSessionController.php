<?php

namespace App\Http\Controllers\Api\Seeker\Session;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advisor\Session\AcceptRequest;
use App\Models\SessionSchedule;
use Illuminate\Http\Request;

class DeletSessionController extends Controller
{
    public function __invoke(AcceptRequest  $request)
    {
        $validatedData = $request->validated();


        // Retrieve the session
        $session = SessionSchedule::find($validatedData['session_id']);

        // Check if the session exists
        if (!$session) {
            return $this->handleResponse(message: 'Session not found.', code: 404, status: false);
        }

        // Check if the session belongs to the seeker
        if ($session->seeker_id !== auth()->id()) {
            return $this->handleResponse(message: 'Unauthorized action.', code: 403, status: false);
        }

         // Check if the session status is "Pending"
         if ($session->advisor_approved != 'Pennding') {
            return $this->handleResponse(message: "Can't delete session because the session is already accepted or not accepted.", status: false, code: 403);
        }

        // Delete the session
        $session->delete();

        // Return success message for deletion
        return $this->handleResponse(message: 'Session deleted successfully.');
        }
}
