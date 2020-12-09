<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    //
    protected $table = 'tags';
    protected $fillable = [
        'id',
        'name',

    ];
    public function get_tags()
    {
        $result = DB::table('tags')
            // ->where('tags.gym_id',$id)
            ->get();
        return $result;
    }

    public function get_tag_data($id)
    {
        $result = Service::where('id',$id)->first();
        return $result;
    }


    public function get_tags_front()
    {
        $result = DB::get('tags');
        return $result;
    }


    public function create_tag($objectSave)
    {
        $rowCreated = Service::create($objectSave);
        $response = Service::where('id',$rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_tag($id, $objectSave)
    {
        $update = Service::find($id)->update($objectSave);
        $response = Service::where('id',$id)->first();
        return $response;
    }

    public function delete_tag($id)
    {
        $response = Service::find($id)->delete();

        return $response;
    }
}
