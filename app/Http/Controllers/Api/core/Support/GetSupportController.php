<?php

namespace App\Http\Controllers\Api\core\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RateRequest;
use App\Http\Requests\Core\GetSupportRequest;
use App\Models\GetSupport;
use Illuminate\Http\Request;

class GetSupportController extends Controller
{

    protected $isSeeker;

    public function __construct()
    {
        $this->isSeeker = request()->header('isSeeker');
    }

    public function __invoke(GetSupportRequest $request)
    {
        $validatedData = $request->validated();


        // Set isSeeker value to the validated data
        $validatedData['isSeeker'] = $this->isSeeker;



        $name = $validatedData['name'];
        $email = $validatedData['email'];
        $message = $validatedData['message'];

        try {
            // Check if the email already exists
            $existingSupport = GetSupport::where('email', $email)->first();

            if ($existingSupport) {
                // Email exists, update the existing record
                $existingSupport->update(['name' => $name, 'message' => $message]);
                $getSupport = $existingSupport;
            } else {
                // Email does not exist, create a new record
                $getSupport = GetSupport::create([
                    'name' => $name,
                    'email' => $email,
                    'isSeeker' => $this->isSeeker,
                    'message' => $message,
                ]);
            }

            return $this->handleResponse(data: $getSupport, code: 201);
        } catch (\Exception $e) {
            return $this->handleResponse(message: $e->getMessage(), code: 500);
        }
    }

}
