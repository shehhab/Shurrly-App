<?php

namespace App\Http\Controllers\Api\Seeker\Session;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SessionSchedule;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\Session\SessionDataResources;

class SessionPendingController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $seekerId = auth()->id();

            // Format the session date


            // Retrieve all session data for the advisor
            $sessions = SessionSchedule::where('seeker_id', $seekerId)->
            where('advisor_approved', '!=' ,'Accept')
            ->whereDate('session_date', '>=', Carbon::now()->subDay())
            ->whereRaw('CONCAT(session_date, " ", start_time) >= NOW()')

            ->get();

            // Return the session data as a resource collection
            return $this->handleResponse(message : 'Successfully retrieved session data', data : SessionDataResources::collection($sessions),code : 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
