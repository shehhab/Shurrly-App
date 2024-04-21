<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class RateAdvisorResource extends JsonResource
{
    public function toArray($request): array
    {
        $data =  [
            'advisor_id' => $this->advisor_id,
            'seeker_id' => $this->seeker_id,
            'rate' => $this->rate,
            'message' => $this->message,

        ];



        return $data;

    }
}
