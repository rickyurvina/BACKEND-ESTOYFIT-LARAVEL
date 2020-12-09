<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cities extends Model
{
    protected $fillable = [
        'id','name','province_id',
    ];

    public function get_cities()
    {
        $result = Cities::get();
        return $result;
    }

}
