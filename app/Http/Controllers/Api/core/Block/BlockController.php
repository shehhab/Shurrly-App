<?php

namespace App\Http\Controllers\Api\core\Block;

use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Block\BlockRequest;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
       public function __invoke(BlockRequest $request)
       {
        $validatedData = $request->validated();

        $blockingUser = Auth::user();
        $blockedUserId = $request->blocked_user_id;

        // Check if the blocked_user_id is provided in the request
        if ($blockedUserId !== null) {
            // Check if the blocked_user_id corresponds to an existing user
            if (!Seeker::where('id', $blockedUserId)->exists()) {
                return $this->handleResponse(message: 'The provided blocked_user_id does not exist.', status: false, code: 404);
            }
        }


        // Check if the user is trying to block themselves
        if ($blockedUserId == $blockingUser->id) {
            return $this->handleResponse(message: 'You cannot block yourself.', status: false, code: 400);
        }

        // Get roles of the blocking user and the blocked user
        $blockingUserRole = $blockingUser->roles->pluck('name')->toArray(); //FROM
        $blockedUserRoles = Seeker::find($blockedUserId)->roles->pluck('name')->toArray(); //to

        // Check if the blocking user has permission to block the blocked user
        if (
            (in_array('advisor', $blockedUserRoles) && in_array('seeker', $blockingUserRole)) ||
            (in_array('seeker', $blockedUserRoles) && in_array('advisor', $blockingUserRole))
        ) {
            // Proceed with blocking the user
            $isBlocked = $blockingUser->blocks()->where('blocked_user_id', $blockedUserId)->exists();

            if ($isBlocked) {
                $blockingUser->blocks()->where('blocked_user_id', $blockedUserId)->delete();
                return $this->handleResponse(message: 'User unblocked successfully.');
            } else {
                $blockingUser->blocks()->create(['blocked_user_id' => $blockedUserId]);
                return $this->handleResponse(message: 'User blocked successfully.');
            }
        }

        return $this->handleResponse(message: 'You do not have permission to perform this action.', status: false, code: 403);
    }
}
