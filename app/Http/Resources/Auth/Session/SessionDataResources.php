<?php

namespace App\Http\Resources\Auth\Session;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionDataResources extends JsonResource
{

    public function toArray($request)
    {
        $formattedDate = Carbon::parse($this->session_date)->format('D,M d');
        $startTime = Carbon::parse($this->start_time);
        // Check if the hour is greater than or equal to 12
        $formattedStartTime = $startTime->format('H:i');
        if ($startTime->hour >= 12) {
            // Append "pm" for times after 12:00 (noon)
            $formattedStartTime .= ' pm';
        }
        else{
            $formattedStartTime .= ' am';

        }

        $data =  [
            'id' => $this->id,
            'advisor_name' => $this->advisor->seeker->name,
            'session_date' => $formattedDate,
            'session_duration' => $this->advisor->session_duration ,
            'start_time' =>  $formattedStartTime,
            'advisor_approved' =>$this->advisor_approved
        ];

        // Add 'note' only if it's not null
        if ($this->note !== null) {
            $data['note'] = $this->note;
        }

        return $data;
    }
}
