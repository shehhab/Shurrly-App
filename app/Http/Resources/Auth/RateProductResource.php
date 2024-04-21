<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class RateProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $data =  [
            'product_id' => $this->product_id,
            'seeker_id' => $this->seeker_id,
            'rate' => $this->rate,
            'message' => $this->message

        ];



        return $data;

    }
}
