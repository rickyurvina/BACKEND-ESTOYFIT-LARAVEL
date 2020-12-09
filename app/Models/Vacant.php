<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Vacant extends Model
{
    protected $table = 'vacancies';
    protected $fillable = [
        'id',
        'title',
        'description',
        'is_active',
        'contact',
        'tag1',
        'tag2',
        'tag3'
    ];

}
