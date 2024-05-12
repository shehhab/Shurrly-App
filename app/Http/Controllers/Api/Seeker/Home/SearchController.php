<?php

namespace App\Http\Controllers\Api\Seeker\Home;

use App\Models\Seeker;
use App\Models\Advisor;
use App\Models\RateAdvisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $searchText = $request->input('search');
        $filterBy = $request->input('filter_by');
        $perPage = 6 ;
        if (empty($searchText)) {
            return $this->handleResponse(message: 'Please Enter Text');
        }


        $query = Advisor::query()
            ->whereIn('seeker_id', function ($query) use ($searchText) {
                $query->select('id')
                    ->from('seekers')
                    ->where('name', 'like', "%$searchText%")
                    ->where('approved', 1);
            })
            ->orWhereHas('skills', function ($query) use ($searchText) {
                $query->where('name', 'like', "%$searchText%");
            });


        if ($filterBy === "price") {
            $query->orderBy('offere');
        }

        if ($filterBy === "rate") {
            $query = Advisor::query()
            ->select('advisors.*')
            ->join('rate_advisors', 'advisors.id', '=', 'rate_advisors.advisor_id')
            ->whereIn('rate_advisors.seeker_id', function ($query) use ($searchText) {
                $query->select('id')
                    ->from('seekers')
                    ->where('name', 'like', "%$searchText%");
            })
            ->orderBy('rate', 'desc');
        }

        if ($filterBy === "name") {
            $query = Advisor::query()
            ->whereIn('seeker_id', function ($query) use ($searchText) {
                $query->select('id')
                    ->from('seekers')
                    ->where('name', 'like', "%$searchText%")
                    ->where('approved', 1);
            });

        }


        if ($filterBy === "skill") {
            $query = Advisor::query()
            ->whereIn('seeker_id', function ($query) use ($searchText) {
                $query->select('id')
                    ->from('seekers')
                    ->where('approved', 1);
            })
            ->WhereHas('skills', function ($query) use ($searchText) {
                $query->where('name', 'like', "%$searchText%");
            });

        }


    $advisors = $query->paginate($perPage);

        if ($advisors->isEmpty()) {
            return $this->handleResponse(message: 'Not Found Advisor And Skills');
        }

        $advisorsData = [];

        foreach ($advisors as $advisor) {
                        // Assuming you have a 'profile_image' media in your media library
        $mediaUrl = Advisor::find($advisor->id)->getFirstMediaUrl('advisor_profile_image');
            $skills = $advisor->skills->pluck('name')->toArray();
            $averageRating = RateAdvisor::where('advisor_id', $advisor->id)->avg('rate');

            $advisorData = [
                'name' => $advisor->seeker->name,
                'bio' => $advisor->bio,
                'offer' => $advisor->offere,
                'advisor_profile_image' => $mediaUrl,
                'average_rating' => $averageRating,
                'skills' => $skills,

            ];
            $advisorsData[] = $advisorData;
        }

        // Formatting pagination data
        $paginationData = $this->pagination($advisors);

        return $this->handleResponse(
            status : true,
            message : 'Successfully',
            data : [
                'specialists' => $advisorsData,
                'pagination' => $paginationData,
            ],
        );
    }
}
