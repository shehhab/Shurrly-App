<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'seeker_id',
        'advisor_id'

    ];
    public function advisor()
    {
        return $this->hasOne(Seeker::class, 'id', 'advisor_id');
    }
    public function seeker()
    {
        return $this->hasOne(Seeker::class, 'id', 'seeker_id');
    }
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }
    public function lastMessages()
    {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
    }
    // Define the relationship to the blocks table
    public function blockedByUser()
    {
        // Assuming you have a "blocks" table with foreign keys "seeker_id" and "blocked_user_id"
        return $this->hasMany(Block::class, 'seeker_id', 'seeker_id')
                    ->whereColumn('blocks.blocked_user_id', 'chats.advisor_id')
                    ->orWhere(function ($query) {
                        $query->whereColumn('blocks.blocked_user_id', 'chats.seeker_id')
                            ->whereColumn('blocks.seeker_id', 'chats.advisor_id');
                    });
    }
}
