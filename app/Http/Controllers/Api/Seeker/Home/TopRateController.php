<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RateAdvisor;

class TopRateController extends Controller
{
    public function __invoke()
    {
        $perPage =  6;

        $avgRatings = RateAdvisor::select('advisor_id', DB::raw('AVG(rate) as average_rating'))
                                ->groupBy('advisor_id')
                                ->with('advisor')
                                ->orderBy('average_rating', 'desc')
                                ->paginate($perPage);

        $topRatedAdvisors = [];
        foreach ($avgRatings as $avgRating) {
            $advisor = Advisor::find($avgRating->advisor_id);
            $image = $advisor->getFirstMediaUrl('advisor_profile_image');

            $categoryNames = [];
            foreach ($advisor->skills as $skill) {
                $categoryNames = array_merge($categoryNames, $skill->categories->pluck('name')->toArray());
            }
            $categoryNames = array_unique($categoryNames);

            $topRatedAdvisors[] = [
                'advisor_id' => $avgRating->advisor_id,
                'avg_rate' => number_format($avgRating->average_rating, 1) ?? 0,
                'name' => $advisor->seeker->name,
                'skills' => $advisor->skills->pluck('name')->toArray(),
                'categories' => $categoryNames,
                'image' => $image
            ];
        }

        return $this->handleResponse(
            status:true,
            message:'Successfully',
            data:[
                'top_rated_advisors' => $topRatedAdvisors,
                'pagination' => $this->pagination($avgRatings)

            ],
        );
    }


}
