<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'report',
        'isSeeker',
        'report_from',
        'report_to',
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
