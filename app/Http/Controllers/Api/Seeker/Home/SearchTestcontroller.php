<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Advisor;
use App\Models\RateAdvisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SearchTestcontroller extends Controller
{
    public function __invoke()
    {
        $advisors = RateAdvisor::select('advisor_id', DB::raw('AVG(rate) as average_rating'))
            ->groupBy('advisor_id')
            ->orderBy('average_rating', 'desc')
            ->get();

        $advisorsData = [];

        foreach ($advisors as $advisor) {
            $advisorModel = Advisor::find($advisor->advisor_id);
            $mediaUrl = $advisorModel->getFirstMediaUrl('advisor_profile_image');
            $skills = $advisorModel->skills->pluck('name')->toArray();

            $advisorData = [
                'name' => $advisorModel->seeker->name,
                'bio' => $advisorModel->bio,
                'offer' => $advisorModel->offere,
                'image' => $mediaUrl,
                'avg_rate' => number_format($advisor->average_rating, 1) ?? 0,
                'skills' => $skills,
            ];

            $advisorsData[] = $advisorData;
        }

        return response()->json($advisorsData);
    }
}
