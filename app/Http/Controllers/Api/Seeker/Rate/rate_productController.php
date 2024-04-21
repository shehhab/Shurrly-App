<?php

namespace App\Http\Controllers\Api\Seeker\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RateRequest;
use App\Http\Resources\Auth\RateProductResource;
use App\Models\Product;
use App\Models\Rate;

class rate_productController extends Controller
{
    public function __invoke(RateRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $productId = $validatedData['product_id'];
            $seekerId = $validatedData['seeker_id'];
            $message = $validatedData['message'] ?? null;

            $rate = Rate::updateOrCreate(
                [
                    'product_id' => $productId,
                    'seeker_id' => $seekerId,
                ],
                [
                    'rate' => $validatedData['rate'],
                    'message' => $message,
                ]
            );

            return $this->handleResponse(data : new RateProductResource($rate), code :  200);
        }  catch (\Exception $e) {
            $errorMessage = $e->getMessage() ?: 'An error occurred.';
            return $this->handleResponse(message : $errorMessage, code: 500 , status:false);
        }
    }

}

