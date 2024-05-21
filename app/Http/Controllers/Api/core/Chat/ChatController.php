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
            return $this->handleResponse(message: 'Please enter Chat_Id', code: 404, status: false);
        }

        $chat = Chat::find($id_chat);
        if (!$chat) {
            return $this->handleResponse(message: 'Chat Not Found', code: 404, status: false);
        }

        $chat->load('advisor');

        $advisor = $chat->advisor;
        if (!$advisor) {
            return $this->handleResponse(message: 'Advisor Not Found', code: 404, status: false);
        }

        // Format the chat timestamps
        $chat->time_chat_formatted = $chat->created_at->format('h:i:s A');
        $chat->date_chat_formatted = $chat->created_at->isoFormat('ddd, DD/MM/YYYY');
        $perPage = 15;

        // Paginate messages, ordered by created_at from newest to oldest
        $messages = $chat->messages()->paginate($perPage);

        // Format timestamps for each message
        foreach ($messages as $message) {
            $message->time_message_formatted = $message->created_at->format('h:i');
            $message->date_message_formatted = $message->created_at->isoFormat('ddd, DD/MM/YYYY');
            unset($message->created_at, $message->updated_at);
        }

        $media = asset('Default/profile.jpeg'); // Default image

        // Determine the user ID for the current user
        $userId = auth()->id(); // Assuming you're using Laravel's built-in authentication

        if ($chat->advisor_id == $userId) {
            $seeker = Seeker::find($chat->seeker_id);
            if ($seeker) {
                $media = $seeker->getFirstMediaUrl('seeker_profile_image') ?: $media;
            }
        } elseif ($chat->seeker_id == $userId) {
            $seeker = Seeker::find($chat->advisor_id);
            if ($seeker) {
                $advisor = $seeker->advisor;
                if ($advisor) {
                    $media = $advisor->getFirstMediaUrl('advisor_profile_image') ?: $seeker->getFirstMediaUrl('seeker_profile_image') ?: $media;
                }
            }
        }

        $paginationData = $this->pagination($messages);
        $data =  [
            'id' => $chat->id,
            'advisor_id' => $chat->advisor_id,
            'name' => $advisor->name,
            'image' => $media,
            'time_chat_formatted' => $chat->time_chat_formatted,
            'date_chat_formatted' => $chat->date_chat_formatted,
            'messages' => $messages->items(),
            'pagination' => $paginationData
        ];
        return $this->handleResponse(data: $data);
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
        $message->time_formatted = $createdAt->format('h:i');

        // Remove the fields you don't want to include in the response
        unset($message->updated_at);
        unset($message->created_at);

        event(new NewMessageSent($request->message, $chat, $this->isSeeker));

        return $this->handleResponse(message: 'Message Sent' , data: $message);
        }
}
