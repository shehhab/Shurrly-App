<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Advisor;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;

class HomeController extends Controller
{
    use ResponseTrait;

    public function __invoke()
    {
        $perPage =  6;

        $advisors = Advisor::with('seeker', 'skills')
            ->where('approved', 1)
            ->withAvg('rate_advisors', 'rate')
            ->paginate($perPage);

        $topRatedAdvisors = $advisors->map(function ($advisor) {
            return [
                'id' => $advisor->id,
                'name' => $advisor->seeker->name,
                'photo_url' => $advisor->getFirstMediaUrl('advisor_profile_image'),
                'skills' => $advisor->skills->pluck('name')->toArray(),
                'categories' => $advisor->category->pluck('name'),
                'offer' => $advisor->offer,
                'avg_rate' => $advisor->rate_advisors_avg_rate,
            ];
        });

        $paginationData = $this->pagination($advisors);

        return response()->json([
            'top_rated_advisors' => [
                'data' => $topRatedAdvisors,
                'pagination' =>$paginationData
            ],
        ]);
    }
}
