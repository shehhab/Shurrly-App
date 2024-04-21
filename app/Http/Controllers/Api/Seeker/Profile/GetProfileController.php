<?php

namespace App\Http\Controllers\Api\Seeker\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\GetProfileResource;

class GetProfileController extends Controller
{

        public function __invoke(){
            $seeker = Auth::user();


            return $this->handleResponse(status:true, message:'welcomn'. $seeker->name , data: new GetProfileResource($seeker) );

        }
}


