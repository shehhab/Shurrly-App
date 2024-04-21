<?php
namespace App\Http\Controllers\Api\Seeker\Auth;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\HasErrorResource;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Support\Facades\RateLimiter;




class LoginController extends Controller
{
    public function __invoke(LoginRequest $request , Exception $e)
    {

        $validatedData = $request->validated();

        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {

            $seeker = Auth::user();
            // to retuen message to send many requests login email
            if (RateLimiter::tooManyAttempts('send-message:'.auth()->user(), $perMinute = 3)) {
                $seconds = RateLimiter::availableIn('send-message:'.auth()->user());


                return $this->handleResponse(status:false,message:'too Many Attempts , You may try again in '.$seconds.' seconds.' , code:429);


            }

            RateLimiter::hit('send-message:'.auth()->user());



            if (!auth()->user()->email_verified_at) {
                return $this->handleResponse(status:false, code:200 ,message:'Email not verified! Please verify your email.',data: new LoginResource($seeker));
            }

            return $this->handleResponse(status:true,message:'Welcome Back '. $seeker->name , data: new LoginResource($seeker));
        }
        // to retuen message to send many requests when wrong password

        if (RateLimiter::tooManyAttempts('send-message:'.auth()->user(), $perMinute = 3)) {
            $seconds = RateLimiter::availableIn('send-message:'.auth()->user());


            return $this->handleResponse(status:false,message:'too Many Attempts , You may try again in '.$seconds.'seconds.' , code:429);


        }

        RateLimiter::hit('send-message:'.auth()->user());

        $date = [];

        return $this->handleResponse(data:new HasErrorResource($date), code:401 ,status: false, message: 'Wrong Email Or Password!');



    }
}

