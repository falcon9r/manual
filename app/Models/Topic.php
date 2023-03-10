<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'template_id',
        'chapter_id',
        'html_body'
    ];

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::creating(function ($model){
            $LastOrder = self::query()->max('order') + 1;
            $model->order = $LastOrder;
        });
    }
}
