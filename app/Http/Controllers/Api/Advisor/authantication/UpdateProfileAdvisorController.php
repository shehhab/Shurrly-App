<?php

namespace App\Http\Controllers\Api\Advisor\authantication;

use Carbon\Carbon;
use App\Models\Skill;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Advisor\UpdateProfileResources;
use Spatie\LaravelIgnition\Http\Requests\UpdateConfigRequest;
use App\Http\Requests\Advisor\authantication\UpdateProfileAdvisorRequest;

class UpdateProfileAdvisorController extends Controller
{
    public function __invoke(UpdateProfileAdvisorRequest $request)
    {
        // Retrieve the authenticated user
        $userId = Auth::id();
        $advisor = Advisor::find($userId);
        $seeker = Seeker::find($userId);

        // Validate the request data directly within the update call
        $validatedData = $request->validated();

        // Convert date_birth format to 'Y-m-d'
        if (isset($validatedData['date_birth'])) {
            $validatedData['date_birth'] = Carbon::createFromFormat('d/m/Y', $validatedData['date_birth'])->format('Y-m-d');
        }

        // Check for skills in the request
        if ($request->has('skills')) {
            $generatedSkills = [];
            foreach ($request->skills as $skillName) {
                $skill = Skill::where('name', $skillName)->first();
                if ($skill) {
                    $generatedSkills[] = $skill->id;
                } else {
                    return $this->handleResponse(message: 'Skill not found: ' . '('.$skillName.')', status: false, code: 422);
                }
            }
            // Update advisor's skills if new skills are provided
            $advisor->skills()->sync($generatedSkills);
        } else {
            // If skills are required but not provided in the request
            return $this->handleResponse(message: 'Skills required', status:false, code: 406);
        }

        // Update profile image
        if ($request->hasFile('image')) {
            $advisor->clearMediaCollection('advisor_profile_image');
            $image = $advisor->addMediaFromRequest('image')->toMediaCollection('advisor_profile_image');
        }

        // Update certificates
        if ($request->hasFile('certificates')) {
            $advisor->clearMediaCollection('advisor_Certificates_PDF');
            $certificates = $advisor->addMediaFromRequest('certificates')->toMediaCollection('advisor_Certificates_PDF');
        }

        if ($request->has('days')) {
            $advisor->Day()->delete(); // Delete old days
            $newDays = [];
            foreach ($validatedData['days'] as $dayData) {
                $dayData['available'] = true; // Set 'available' to true
                $newDays[] = $dayData;
            }
            $advisor->Day()->createMany($newDays); // Create new days
        }



        // Update the advisor with validated data
        $advisor->update($validatedData);

        // Return success response
        return $this->handleResponse(status: true, message: 'Successfully updated profile for '. $seeker->email, data: new UpdateProfileResources($advisor));
    }


}
