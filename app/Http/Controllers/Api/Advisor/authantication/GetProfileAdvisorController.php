<?php

namespace App\Http\Controllers\Api\Advisor\authantication;

use App\Models\Advisor;
use App\Http\Controllers\Controller;
use App\Http\Resources\Advisor\GetProfileAdvisorResources;
use App\Http\Requests\Advisor\authantication\GetProfileAdvisorRequest;

class GetProfileAdvisorController extends Controller
{
    public function __invoke(GetProfileAdvisorRequest $request)
    {
        $validatedData = $request->validated();

        $advisor = Advisor::find($validatedData['id']);

        if (!$advisor) {
            return  $this->handleResponse( status: false ,  message: 'Advisor not found', code : 404);
        }


        $data = [
            'message' => new GetProfileAdvisorResources($advisor),
            //'days' => new DayResources(['days' => $advisor->days]),
        ];

        return $this->handleResponse(
            message: 'Profile advisor',
            data : $data
        );
    }

}
