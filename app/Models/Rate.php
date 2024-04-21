<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'rate', 'seeker_id' , 'message'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }


}
