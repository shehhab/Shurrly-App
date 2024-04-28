<?php

namespace App\Http\Controllers\Api\core\Chat;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Block;
use App\Models\Seeker;
use App\Models\Advisor;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Events\chat\NewMessageSent;
use App\Http\Controllers\Controller;

class ChatController  extends Controller
{
    protected $isSeeker;
    protected $authUser;
    protected $mainKey;
    protected $secondaryKey;

    public function __construct()
    {

        $this->isSeeker = request()->header('isSeeker');
        if ($this->isSeeker) {
            $this->mainKey =   'seeker_id';
            $this->secondaryKey =   'advisor_id';
        } else {
            $this->mainKey =   'advisor_id';
            $this->secondaryKey =   'seeker_id';
        }
    }
    public function index(Request $request)
    {
        $limit = $request->limit ?? 25;
        $chats = Chat::where($this->mainKey, auth()->user()->id)->with(['messages', 'lastMessages'])->paginate($limit);

        // Transforming timestamps for each message in the chat
        $chats->getCollection()->transform(function ($chat) {
            $chat->messages->each(function ($message) {
                $createdAt = Carbon::parse($message->created_at);
                $message->date_formatted = $createdAt->isoFormat('ddd, DD/MM/YYYY');
                $message->time_formatted = $createdAt->format('h:i:s A');

                // Defining the current day and the previous day
                $today = Carbon::today();
                $yesterday = Carbon::yesterday();

                // Set the appropriate text for history
                if ($createdAt->isSameDay($today)) {
                    $message->date_formatted = 'Today';
                } elseif ($createdAt->isSameDay($yesterday)) {
                    $message->date_formatted = 'Yesterday';
                }
            });

            // Transforming timestamp for last message in the chat
            $lastMessageCreatedAt = Carbon::parse($chat->lastMessages->created_at);
            $chat->lastMessages->date_formatted = $lastMessageCreatedAt->isoFormat('ddd, DD/MM/YYYY');
            $chat->lastMessages->time_formatted = $lastMessageCreatedAt->format('h:i:s A');

            // Defining the current day and the previous day for last message
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();

            // Set the appropriate text for history for last message
            if ($lastMessageCreatedAt->isSameDay($today)) {
                $chat->lastMessages->date_formatted = 'Today';
            } elseif ($lastMessageCreatedAt->isSameDay($yesterday)) {
                $chat->lastMessages->date_formatted = 'Yesterday';
            }

            // Remove created_at and updated_at from last message
            unset($chat->lastMessages->created_at);
            unset($chat->lastMessages->updated_at);

            return $chat;
        });

        // Removing created_at and updated_at from every message
        $chats->getCollection()->each(function ($chat) {
            $chat->messages->each(function ($message) {
                unset($message->created_at, $message->updated_at);
            });
        });

        // Format the created_at timestamp for each chat
        $chats->each(function ($chat) {
            $createdAt = Carbon::parse($chat->created_at);
            $chat->date_caht_formatted = $createdAt->isoFormat('ddd, DD/MM/YYYY');
            $chat->time_caht_formatted = $createdAt->format('h:i A');
            unset($chat->updated_at);
            unset($chat->created_at);
        });

        return $this->handleResponse(data: $chats);
    }

    public function sendMessage(Request $request)
    {
        $recipientId = $request->userId;
        $senderId = auth()->user()->id;

        // Check if a user is blocked
        $isBlockedByRecipient = Block::where('user_id', $recipientId)->where('blocked_user_id', $senderId)->exists();
        $isBlockedBySender = Block::where('user_id', $senderId)->where('blocked_user_id', $recipientId)->exists();

        if ($isBlockedByRecipient || $isBlockedBySender) {
            return $this->handleResponse(message: 'You are blocked by this user.', status: false, code: 403);
        }



        if (!Seeker::find($request->userId)) {
            return $this->handleResponse(message: 'User Not Found', code: 404 ,status: false);
        }

        if (auth()->user()->id === $request->userId) return $this->handleResponse(message: 'Cannot send to yourself');
        $chat = Chat::firstOrCreate([
            $this->mainKey => auth()->user()->id,
            $this->secondaryKey => $request->userId
        ]);
        $message = ChatMessage::create(['chat_id' => $chat->id, 'message' => $request->message, 'isSeeker' => $this->isSeeker]);

        // Format the created_at timestamp
        $createdAt = $message->created_at;
        //$message->date_formatted = $createdAt->isoFormat('ddd, DD/MM/YYYY');
        $message->time_formatted = $createdAt->format('h:i:s A');

        // Remove the fields you don't want to include in the response
        unset($message->updated_at);
        unset($message->created_at);

        event(new NewMessageSent($request->message, $chat, $this->isSeeker));

        return $this->handleResponse(message: 'Message Sent' , data: $message);
        }
}
