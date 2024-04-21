<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = ['name',
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


}
