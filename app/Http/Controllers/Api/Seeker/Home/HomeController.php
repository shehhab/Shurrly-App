<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Skill;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke()
    {

    $avgRatings = DB::table('rate_advisors')
        ->select('advisor_id', DB::raw('AVG(rate) as avg_rate'))
        ->groupBy('advisor_id')
        ->get();

    $advisors = Advisor::with('seeker')
        ->where('approved', 1)
        ->get();

    $formattedAdvisors = $advisors->map(function ($advisor) use ($avgRatings) {
        $avgRating = $avgRatings->firstWhere('advisor_id', $advisor->id);
        $avgRate = $avgRating ? $avgRating->avg_rate : null;

        return [
            'id' => $advisor->id,
            'name' => $advisor->seeker->name,
            'photo_url' => $advisor->getFirstMediaUrl('advisor_profile_image'),
            'skills' => $advisor->skills->pluck('name')->toArray(),
            'avg_rate' => $avgRate,
        ];
    });

    $topRatedAdvisors = $formattedAdvisors->sortByDesc('avg_rate')->take(5);

    $skills = Skill::pluck('name', 'id');

    return $this->handleResponse(data: [
        'top_rated_advisors' => $topRatedAdvisors->values()->all(),
        'all_advisors' => $formattedAdvisors->values()->all(),
        'Skills' => $skills,
    ]);

    }
}
