<?php

namespace App\Http\Resources\Advisor;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateProfileResources extends JsonResource
{
    public function toArray($request): array
    {
        // Get the authenticated user ID
        $userId = Auth::id();

        // Find the advisor based on the authenticated user ID
        $seeker = Seeker::find($userId);


        $dateOfBirth = $seeker->date_birth ? \Carbon\Carbon::createFromFormat('Y-m-d', $seeker->date_birth)->format('d-m-Y') : null;

        if ($request->has('date_birth')) {
            $dateOfBirth = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_birth)->format('Y-m-d');
            $seeker->update(['date_birth' => $dateOfBirth]);
            // Update $dateOfBirth for response
            $dateOfBirth = \Carbon\Carbon::createFromFormat('Y-m-d', $seeker->date_birth)->format('d-m-Y');
        }
        else{
            $dateOfBirth = \Carbon\Carbon::createFromFormat('Y-m-d', $seeker->date_birth)->format('d-m-Y');

        }

        $defaultImage = asset('Default/profile.jpeg');
        return [
            'bio' => $this->bio,
            "name"  => $this->whenHas('name'),
            "date_birth" => $dateOfBirth,
            'image' => $this->getFirstMediaUrl('advisor_profile_image'),
            'video' => $this->getFirstMediaUrl('advisor_Intro_video'),
            'certificates' => $this->getFirstMediaUrl('advisor_Certificates_PDF'),
            'offere' => $this->offere,
            'roles' => $seeker->roles->filter(function ($role) {
                return $role->name === 'advisor';
            })->pluck('name')->toArray(),

            "Skills" => $this->Skills->pluck('name'),
            "available" => $this->Day,
        ];
    }
}
