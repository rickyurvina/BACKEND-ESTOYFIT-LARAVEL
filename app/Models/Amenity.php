<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Amenity extends Model
{
    //
    protected $table = 'amenities';
    protected $fillable = [
        'id',
        'name',
        'description',
        'main_image'
    ];

    public function get_amenities()
    {
        $result = DB::table('amenities')
            // ->where('amenities.gym_id',$id)
            ->get();
        return $result;
    }

    public function get_amenity_data($id)
    {
        $result = Amenity::where('id',$id)->first();
        return $result;
    }


    public function get_amenities_front()
    {
        $result = DB::get('amenities');
        return $result;
    }


    public function create_amenity($objectSave)
    {
        $rowCreated = Amenity::create($objectSave);
        $response = Amenity::where('id',$rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_amenity($id, $objectSave)
    {
        $update = Amenity::find($id)->update($objectSave);
        $response = Amenity::where('id',$id)->first();
        return $response;
    }

    public function delete_amenity($id)
    {
        $response = Amenity::find($id)->delete();
        return $response;
    }
}
