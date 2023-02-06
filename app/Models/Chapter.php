<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'title',
    ];

    public function topics(){
        return $this->hasMany(Topic::class)->orderBy('order');
    }

}
