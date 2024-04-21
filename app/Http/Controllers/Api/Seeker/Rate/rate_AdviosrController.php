<?php

namespace App\Http\Controllers\Api\Seeker\Rate;

use App\Models\Advisor;
use App\Models\RateAdvisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Rate_AdvisorRequest;
use App\Http\Resources\Auth\RateAdvisorResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class rate_AdviosrController extends Controller
{
    public function rateAdvisor(Rate_AdvisorRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $advisorId = $validatedData['advisor_id'];
            $seekerId = $validatedData['seeker_id'];
            $message = $validatedData['message'] ?? null;



            $advisor = Advisor::findOrFail($advisorId);

            $rate = RateAdvisor::updateOrCreate(
                [
                    'advisor_id' => $advisorId,
                    'seeker_id' => $seekerId,
                ],
                [
                    'rate' => $validatedData['rate'],
                    'message' => $message,
                ]
            );

            return $this->handleResponse(data : new RateAdvisorResource($rate), code :  200);
        } catch (ModelNotFoundException $e) {
            return $this->handleResponse(message :'Advisor not found', code: 404 , status:false);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage() ?: 'An error occurred.';
            return $this->handleResponse(message : $errorMessage, code: 500 , status:false);
        }
    }
}
