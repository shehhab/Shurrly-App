<?php

namespace App\Http\Resources\Advisor;

use Carbon\Carbon;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class GetProfileAdvisorResources extends JsonResource
{
    public function toArray($request)
    {
        // Fetching the advisor associated with this resource
        $advisor = Advisor::with('skills')->find($this->id);

        if ($advisor) {
            $skills = $advisor->skills ? $advisor->skills->pluck('name')->toArray() : [];

            $media = $advisor->getFirstMediaUrl('advisor_profile_image');
            $certificates = $advisor->getFirstMediaUrl('advisor_Certificates_PDF');
            $seeker = $advisor->seeker;
            $days = $advisor->Day;

            $dateOfBirth = $seeker->date_birth ? Carbon::createFromFormat('Y-m-d', $seeker->date_birth)->format('d-m-Y') : null;

            $availableDays = $advisor->Day()->where('available', true)->get();

            $availableTimes = [];
            $sessionDuration = strtotime('1970-01-01 ' . $advisor->session_duration . ' UTC');

            foreach ($availableDays as $day) {
                $startTime = strtotime($day->from);
                if (!empty($day->break_from)) {
                    $breakFromTime = strtotime($day->break_from);
                    $breakToTime = strtotime($day->break_to);

                    // Add available times before break starts
                    while ($startTime < $breakFromTime) {
                        $timeLabel = date("h:i A", $startTime);
                        $availableTimes[] = [
                            'time' => $timeLabel,
                            'day' => $day->day,
                        ];
                        $startTime += $sessionDuration;
                    }

                    // Skip times during break
                    $startTime = $breakToTime;
                }

                // Add available times after break
                $endTime = strtotime($day->to);
                while ($startTime <= $endTime) {
                    $timeLabel = date("h:i A", $startTime);
                    $availableTimes[] = [
                        'time' => $timeLabel,
                        'day' => $day->day,
                    ];
                    $startTime += $sessionDuration;
                }
            }

            return [
                "advisor_id" => $advisor->id,
                "uuid" => $seeker->uuid,
                'email_verified' => (bool)$seeker->email_verified_at, // Changed $this->email_verified_at to $seeker->email_verified_at
                "name" => $seeker->name,
                "email" => $seeker->email,
                'bio' => $advisor->bio,
                "date_birth" => $dateOfBirth,
                'image' => $media,
                'certificates' => $certificates,
                'offer' => $advisor->offere,
                "skills" => $skills,

                'roles' => $seeker->roles->filter(function ($role) {
                    return $role->name === 'advisor';
                })->pluck('name')->toArray(),



                "days" => $days,
                'available_times' => $availableTimes,
            ];
        } else {
            return [];
        }
    }
}


