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
        $id_chat = $request->input('id_chat');
        if (empty($id_chat)) {
            return $this->handleResponse(message: 'Please enter Chat_Id' , code : 404 , status : false );
        }



        $chat = Chat::find($id_chat);

        if (!$chat) {
            return $this->handleResponse(message: 'Chat Not Found' );
        }
        $chat->load(['messages', 'advisor']);

        $advisor = $chat->advisor;

        if (!$advisor) {
            return null; // Skip if no advisor is found
        }

        // Format the chat timestamps
        $chat->time_chat_formatted = $chat->created_at->format('h:i:s A');
        $chat->date_chat_formatted = $chat->created_at->isoFormat('ddd, DD/MM/YYYY');
    // Format timestamps for each message
    foreach ($chat->messages as $message) {
        $message->time_message_formatted = $message->created_at->format('h:i:s A');
        $message->date_message_formatted = $message->created_at->isoFormat('ddd, DD/MM/YYYY');
        unset($message->created_at , $message->updated_at);
    }
        $advisorModel = Advisor::find($chat->advisor_id);
        $mediaUrl = $advisorModel ? $advisorModel->getFirstMediaUrl('advisor_profile_image') : null;
        return [
            'id' => $chat->id,
            'advisor_id' => $chat->advisor_id,
            'name' => $advisor->name,
            'image' => $mediaUrl ?: null,
            'time_chat_formatted' => $chat->time_chat_formatted,
            'date_chat_formatted' => $chat->date_chat_formatted,
            'messages' => $chat->messages,
        ];
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
