<?php

namespace App\Http\Controllers\Api\Advisor\Session;

use Carbon\Carbon;
use App\Models\SessionSchedule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advisor\Session\AcceptRequest;
use Illuminate\Support\Facades\Http;
class Accept_seesion_advisorController extends Controller
{
    public function __invoke(AcceptRequest $request) {
        try {
            $validatedData = $request->validated();

            // Retrieve the session to be updated or return error if not found
            $session = SessionSchedule::find($validatedData['session_id']);
            if (!$session) {
                return $this->handleResponse(message: 'Session not found.', code: 404, status: false);
            }

            // Check if the session belongs to the advisor
            if ($session->advisor->seeker_id !== auth()->id()) {
                return $this->handleResponse(message: 'Unauthorized action.', code: 403, status: false);
            }

            // Check if the session has already been approved by the advisor
            if ($session->advisor_approved === "Accept") {
                return $this->handleResponse(message: 'Session already approved.', code: 400, status: false);
            }

            // If the session date is not at least one day ahead, return an error
            if (Carbon::parse($session->session_date)->isToday() || Carbon::parse($session->session_date)->isPast()) {
                return $this->handleResponse(message: 'Session date should be at least one day ahead of today.', code: 400, status: false);
            }

            // Create Zoom meeting
            $meetingData = $this->createMeeting($session);

            // Update session with meeting link
            $session->linkseesion = $meetingData['join_url'];
            $session->advisor_approved = "Accept";
            $session->save();

            // Return the appropriate response
            return $this->handleResponse(message: 'Session accepted successfully.',
            data:[ 'Link_Meeting' => $session->linkseesion]
        );
        } catch (\Exception $e) {
            return $this->handleResponse(message: $e->getMessage(), code: 500, status: false);
        }
    }

    public function createMeeting(SessionSchedule $session): array {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::generateToken(),
                'Content-Type' => 'application/json',
            ])->post("https://api.zoom.us/v2/users/me/meetings", [
                'topic' => 'Session with ' . $session->client_name, // Adjust topic if needed
                'type' => 2, // 2 for scheduled meeting
                'start_time' => Carbon::parse($session->session_date)->toIso8601String(),
                'duration' => $session->duration_in_minutes, // Assuming this property exists in SessionSchedule model
            ]);

            // Get the join_url from the response and add it to the data
            return ['join_url' => $response->json()['join_url']];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    protected function generateToken(): string
    {
        try {
            $base64String = base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET'));
            $accountId = env('ZOOM_ACCOUNT_ID');

            $responseToken = Http::withHeaders([
                "Content-Type"=> "application/x-www-form-urlencoded",
                "Authorization"=> "Basic {$base64String}"
            ])->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$accountId}");

            return $responseToken->json()['access_token'];

        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
