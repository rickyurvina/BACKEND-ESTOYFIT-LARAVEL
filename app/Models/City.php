<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable = [
        'id','name'
    ];

    public function get_cities()
    {
        $result = City::get();
        return $result;
    }
}
