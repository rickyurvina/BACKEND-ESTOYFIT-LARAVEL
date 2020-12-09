<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $fillable = [
        'id','name'
    ];

    public function get_provinces()
    {
        $result = Provinces::get();
        return $result;
    }
}
