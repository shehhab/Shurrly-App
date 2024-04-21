<?php

namespace App\Http\Controllers\Api\core\Chat;

use App\Models\Chat;
use App\Models\Seeker;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Events\chat\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Advisor;
use App\Models\Block;

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
        $chat = Chat::where($this->mainKey, auth()->user()->id)->with(['messages', 'lastMessages'])->paginate($limit);
        return $this->handleResponse(data: $chat);
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
        event(new NewMessageSent($request->message, $chat, $this->isSeeker));

        return $this->handleResponse(message: 'Message Sent' , data: $message);
    }
}
