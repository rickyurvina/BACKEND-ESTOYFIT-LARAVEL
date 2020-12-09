<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Publicity extends Model
{
    //
    protected $table = 'publicities';
    protected $fillable = [
        'id',
        'url',
        'title',
        'subtitle'
    ];


    public function get_publicity()
    {
//        return "si llego hasta modelo";
//        $result = DB::table('sliderhome')
//            // ->where('publicity.gym_id',$id)
//            ->get();
        $result=Publicity::get();

        return $result;
    }

    public function get_publicity_data($id)
    {
        $result = Publicity::where('id',$id)->first();
        return $result;
    }


    public function get_publicity_front()
    {
        $result = DB::get('publicity');
        return $result;
    }


    public function create_publicity($objectSave)
    {
        $rowCreated = Publicity::create($objectSave);
        $response = Publicity::where('id',$rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_publicity($id, $objectSave)
    {
        $update = Publicity::find($id)->update($objectSave);
        $response = Publicity::where('id',$id)->first();
        return $response;
    }

    public function delete_publicity($id)
    {
        $response = Publicity::find($id)->delete();
        return $response;
    }
}
