<?php

namespace App\Http\Controllers\Api\core\Chat;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $blockedUsers = Block::where('user_id', $userId)
            ->orWhere('blocked_user_id', $userId)
            ->get()
            ->pluck('blocked_user_id');

        $chats->each(function ($chat) use ($blockedUsers) {
            $chat->is_blocked = $blockedUsers->contains($chat->seeker_id) || $blockedUsers->contains($chat->advisor_id);
            $this->formatChatTimestamps($chat);
        });

        return response()->json(['chats' => $chats], 200);
    }

    private function formatChatTimestamps($chat)
    {
        $chat->time_chat_formatted = $chat->created_at->format('h:i:s A');
        $chat->date_chat_formatted = $chat->created_at->isoFormat('ddd, DD/MM/YYYY');

        if ($chat->lastMessages) {
            $lastMessageCreatedAt = Carbon::parse($chat->lastMessages->created_at);
            $chat->lastMessages->date_formatted = $lastMessageCreatedAt->isToday() ? 'Today' : ($lastMessageCreatedAt->isYesterday() ? 'Yesterday' : $lastMessageCreatedAt->isoFormat('ddd, DD/MM/YYYY'));
            $chat->lastMessages->time_formatted = $lastMessageCreatedAt->format('h:i:s A');

            unset($chat->lastMessages->created_at, $chat->lastMessages->updated_at);
        }

        unset($chat->updated_at, $chat->created_at);
    }
        }
