<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cantones extends Model
{
    protected $fillable = [
        'id','name'
    ];

    public function get_cantones()
    {
        $result = Cantones::get();
        return $result;
    }
}
