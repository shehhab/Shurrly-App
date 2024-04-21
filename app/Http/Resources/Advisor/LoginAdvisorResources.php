<?php

namespace App\Http\Resources\Advisor;

use Carbon\Carbon;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginAdvisorResources extends JsonResource
{
    public function toArray($request)
    {
        $advisor = Auth::user();
        $dateOfBirth = $this->date_birth ? Carbon::createFromFormat('Y-m-d', $this->date_birth)->format('d-m-Y') : null;
        $userId =  Auth::id();
        $user = Advisor::find($userId);

        if (!$user) {
            $user = Advisor::where('seeker_id', $advisor->id)->first();
        }

        $media = null;
        $skills = null;
        $certificates = null;

        if ($user) {
            $media = $user->getFirstMediaUrl('advisor_profile_image');
            $certificates = $user->getFirstMediaUrl('advisor_Certificates_PDF');
            $skills = $user->skills->pluck('name')->toArray();
        }



        return [
            "id" => $user->id,
            'email_verified' => (bool) $this->email_verified_at ? true : false,
            'token' => $this->createToken('auth_token')->plainTextToken,
            "name" => (string) $this->whenHas('name'),
            "email" => (string) $this->whenHas('email'),
            "date_birth" => $dateOfBirth,
            'image' => $media,
            'certificates' => $certificates,
            "bio" => $user->bio,
            "offer" => $user->offere,
            'category' => $user->category->name,
            "language" => $user->language,
            'session_duration' => $user->session_duration,
            "country" => $user->country,
            'roles' => $this->when($this->hasRole('advisor'), function () {
                return $this->roles->filter(function ($role) {
                    return $role->name === 'advisor';
                })->pluck('name');
            }),
            'roles.permissions' => $this->getPermissionsViaRoles()->pluck(['name']) ?? [],
            "skills" => $skills,
        ];
    }
}
