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
        $perPage = 6;

        if (empty($searchText)) {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'Please Enter Text',
                'data' => []
            ]);
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

        switch ($filterBy) {
            case 'price':
                $query->orderBy('offere');
                break;

            case 'rate':
                $query->withAvg('rates', 'rate')->orderByDesc('rates_avg_rate');
                break;

            case 'name':
                $query->join('seekers', 'advisors.seeker_id', '=', 'seekers.id')
                      ->where('seekers.name', 'like', "%$searchText%")
                      ->where('advisors.approved', 1)
                      ->select('advisors.*');
                break;

            case 'skill':
                $query =  Advisor::query()

                ->orWhereHas('skills', function ($query) use ($searchText) {
                    $query->where('name', 'like', "%$searchText%");
                });

                break;
        }

        $advisors = $query->paginate($perPage);
        $advisor = $query->get();

        if ($advisor->isEmpty()) {
            return $this->handleResponse(message: 'Not Found Data Please Enter, A Valid Text',data: $advisor);
        }



        $advisorsData = [];
        foreach ($advisors as $advisor) {
            $advisorsData[] = $this->getAdvisorData($advisor);
        }

        $paginationData = $this->pagination($advisors);

        return $this->handleResponse(
            status: true,
            message: 'Successfully',
            data: [
                'specialists' => $advisorsData,
                'pagination' => $paginationData,
            ]
        );
    }

    private function getAdvisorData($advisor)
    {
        $advisorModel = Advisor::with('skills', 'seeker')->find($advisor->id);
        $mediaUrl = $advisorModel->getFirstMediaUrl('advisor_profile_image');
        $skills = $advisorModel->skills->pluck('name')->toArray();
        $averageRating = RateAdvisor::where('advisor_id', $advisor->id)->avg('rate');

        return [
            'name' => $advisorModel->seeker->name ?? 'N/A',
            'bio' => $advisorModel->bio,
            'offer' => $advisorModel->offere, // corrected 'offere' to 'offer'
            'image' => $mediaUrl,
            'avg_rate' => number_format($averageRating, 1) ?? 0,
            'skills' => $skills,
        ];
    }
}
