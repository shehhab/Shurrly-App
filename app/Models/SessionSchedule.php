<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "seeker_id",
        "advisor_id",
        "session_date",
        "note",
        "start_time",
        "advisor_approved",
        "seeker_history",
        "advisor_history",
        'linkseesion'

    ];
    public function seeker()
    {
        return $this->belongsTo(Seeker::class, 'seeker_id');
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'advisor_id');
    }

}
