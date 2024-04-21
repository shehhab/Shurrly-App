<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Block extends Model
{
    use HasFactory;
    protected $fillable = [
    'blocked_user_id'
    ];

    public function blockedUser(): BelongsTo
    {
        return $this->belongsTo(Seeker::class, 'blocked_user_id', 'id');
    }
}
