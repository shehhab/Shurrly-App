<?php

namespace App\Http\Controllers\Api\Advisor\Session;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SessionSchedule;
use App\Http\Controllers\Controller;
use App\Http\Resources\Advisor\Session\SessionDataResources;

class SessionHistoryController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $advisorId = auth()->id();

            // Retrieve all session data for the advisor
            $sessions = SessionSchedule::where('advisor_id', $advisorId)
            ->where('advisor_history', 1)
            ->whereDate('session_date', '<=', Carbon::now())
            ->whereRaw('CONCAT(session_date, " ", start_time) <= NOW()')
            ->get();


            // Return the session data as a resource collection
            return $this->handleResponse(message : 'Successfully retrieved session data', data : SessionDataResources::collection($sessions),code : 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
