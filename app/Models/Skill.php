<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;


class Skill extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $fillable = [
    'name',
    'public',
    "categories_id"
];

    // * Relationship

    public function advisors(){
        return $this->belongsToMany(Advisor::class);
    }

    public function categories(){
        return $this->belongsTo(Category::class,'categories_id');
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image_catogory');
    }

}
