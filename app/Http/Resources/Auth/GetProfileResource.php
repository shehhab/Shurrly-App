<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        $dateOfBirth = $this->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;

        $defaultImage = asset('Default/profile.jpeg');
        return [
            "id" => $this->whenHas('id'),
            "uuid" => $this->whenHas('uuid'),
            'email_verfied'=>(bool) $this->email_verified_at ? true:false,
            "name" =>(string) $this->whenHas('name'),
            "email" => (string) $this->whenHas('email'),
            "date_birth" => $dateOfBirth,
            'image'=> (string) $this->getFirstMediaUrl('seeker_profile_image')?:$defaultImage,
            'roles' => $this->when($this->hasRole('seeker'), function () {
                return $this->roles->filter(function ($role) {
                    return $role->name === 'seeker';
                })->pluck('name');
            }),
                ];
    }
}
