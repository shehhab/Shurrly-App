<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Advisor extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasRoles;
    protected $guard_name = 'web';
    protected $fillable = [
        'bio',
        'expertise',
        'seeker_id',
        'offere',
        'language',
        'country',
        'approved',
        'category_id',
        'session_duration',

    ];

    //upload advisor_profile_image
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('advisor_profile_image');
    }

    //upload advisor_Intro_video
    public function createMediaProduct(): void
    {
        $this->addMediaCollection('advisor_Intro_video');
    }

    //upload advisor_Certificates_PDF
    public function createMediaCertificates(): void
    {
        $this->addMediaCollection('advisor_Certificates_PDF');
    }

    // * Relationship
    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }

    public function Day()
    {
        return $this->hasMany(Day::class, 'advisor_id','id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);

        //$projects = $advisor->projects;

        //$advisor = $product->advisor;
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function receivedSessions()
    {
        return $this->hasMany(SessionSchedule::class, 'advisor_id');
    }

    public function receivedRatings()
    {
        return $this->belongsToMany(Seeker::class, 'rate_advisors', 'advisor_id', 'seeker_id')
                    ->withPivot('rate', 'message')
                    ->withTimestamps();
    }
        // Define the relationship with RateAdvisor
        public function rate_advisors()
        {
            return $this->hasMany(RateAdvisor::class);
        }
        public function rates()
        {
            return $this->hasMany(RateAdvisor::class, 'advisor_id');
        }


}
