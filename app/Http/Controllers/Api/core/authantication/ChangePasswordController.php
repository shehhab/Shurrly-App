<?php

namespace App\Http\Controllers\Api\core\authantication;

use App\Models\Seeker;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use App\Http\Requests\Auth\changePasswordRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class ChangePasswordController extends Controller
{
    public function __invoke(changePasswordRequest $request)
    {
        $validatedData = $request->validated();



    // Get the authenticated user
    $user = Auth::user();

    // Check if the current password matches the one in the database
    if (!Hash::check($request->current_password, $user->password)) {
        return $this->handleResponse(message:'Current password is incorrect', code:401 , status:false  );

    }

    // Hash the new password
    $newPassword = Hash::make($request->new_password);

    // Update the user's password
    $user->password = $newPassword;
    $user->save();

    //updated successfully
    return $this->handleResponse(message:'Password updated successfully');

}
}
