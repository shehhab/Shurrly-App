<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Advisor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Request;
use App\Traits\ResponseTrait;

class SpecialistsController extends Controller
{
    use ResponseTrait;

    public function __invoke(Request $request)
    {
        // Retrieve the skill from the request
        $skill = $request->input('skill');

        // Number of results per page
        $perPage = 6;

        // Query to retrieve advisors with their relationships and average ratings
        $query = Advisor::with('seeker', 'skills')
            ->where('approved', 1);

        // Check if the skill is provided
        if ($skill) {
            $query->whereHas('skills', function ($query) use ($skill) {
                $query->where('name', $skill);
            });
        }

        $advisors = $query->withAvg('rate_advisors', 'rate')
            ->paginate($perPage);

        // Transforming the data into a specific format
        $topRatedAdvisors = $advisors->map(function ($advisor) {
            return [
                'id' => $advisor->id,
                'name' => $advisor->seeker->name,
                'image' => $advisor->getFirstMediaUrl('advisor_profile_image'),
                'skills' => $advisor->skills->pluck('name')->toArray(),
                'categories' => $advisor->skills->map(function ($skill) {
                    return $skill->categories->name ;
                })->toArray(),
                        'offer' => $advisor->offere,
                        'avg_rate' => number_format($advisor->rate_advisors_avg_rate, 2) ?? 0, // تنسيق الرقم بعدد أرقام عشرية محددة
                    ];
        });

        // Formatting pagination data
        $paginationData = $this->pagination($advisors);

        return $this->handleResponse(
            status : true,
            message:  'Successfully',
            data: [
                'specialists' => $topRatedAdvisors,
                'pagination' => $paginationData
            ],
        );
    }

}
