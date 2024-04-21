<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request): array
    {
        // Format date_birth using Carbon
        $this->tokens()->delete();
        $dateOfBirth = $this->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;

        $defaultImage = asset('Default/profile.jpeg');
        return [
            "id" => $this->whenHas('id'),
            "uuid" => $this->whenHas('uuid'),
            'email_verified'=>(bool) $this->email_verified_at ? true:false,
            'token' => $this->createToken('auth_token')->plainTextToken,
            "name" =>(string) $this->whenHas('name'),
            "email" => (string) $this->whenHas('email'),
            "date_birth" => $dateOfBirth,
            'image'=> (string) $this->getFirstMediaUrl('seeker_profile_image')?:$defaultImage,
            'roles' => $this->when($this->hasRole('seeker'), function () {
                return $this->roles->filter(function ($role) {
                    return $role->name === 'seeker';
                })->pluck('name');
            }),

            'roles.permissions' => $this->getPermissionsViaRoles()->pluck(['name']) ?? [],

            //"role" => $this->when($this->hasRole('seekre'), 'seeker', 'seeker'),
        ];
    }
}
