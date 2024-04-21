<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateAdvisor extends Model
{
    use HasFactory;
    protected $fillable = [
        'advisor_id',
        'seeker_id',
        'rate',
        'message',
    ];
        public function seeker()
        {
            return $this->belongsTo(Seeker::class);
        }

        public function advisor()
        {
            return $this->belongsTo(Advisor::class);
        }
}
