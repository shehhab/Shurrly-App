<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionScheduleResource extends JsonResource
{
    public function toArray($request): array
    {
        $data =  [
            'id' => $this->id,
            'advisor_id' => $this->advisor_id,
            'seeker_id' => $this->seeker_id,
            'start_time' => $this->start_time,

            'session_date' => $this->session_date,
            'note' => $this->note,

        ];



        return $data;

    }
}
