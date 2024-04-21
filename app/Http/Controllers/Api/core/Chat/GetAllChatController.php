<?php

namespace App\Http\Controllers\Api\core\Chat;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Database\Eloquent\Builder;


class GetAllChatController extends Controller
{
    public function __invoke(Request $request)
    {

        $userId = auth()->user()->id;

        $chats = Chat::where('seeker_id', $userId)
            ->orWhere('advisor_id', $userId)
            ->with(['lastMessages'])
            ->get();

        // Get blocked users for the current user
        $blockedUsers = Block::where('user_id', $userId)
            ->orWhere('blocked_user_id', $userId)
            ->get()
            ->pluck('blocked_user_id');

        // Iterate over chats and check if the user is blocked
        $chats->each(function ($chat) use ($blockedUsers) {
            $chat->is_blocked =
                $blockedUsers->contains($chat->seeker_id) || $blockedUsers->contains($chat->advisor_id);
        });

        return response()->json(['chats' => $chats], 200);
}
}
