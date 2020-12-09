<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parroquias extends Model
{
    //
    protected $fillable = [
        'id','name'
    ];

    public function get_parroquias()
    {
        $result = Parroquias::get();
        return $result;
    }
}
