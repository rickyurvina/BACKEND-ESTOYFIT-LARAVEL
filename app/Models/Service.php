<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    public function get_services()
{
    $result = DB::table('services')
        // ->where('services.gym_id',$id)
        ->get();
    return $result;
}

    public function get_service_data($id)
    {
        $result = Service::where('id',$id)->first();
        return $result;
    }


    public function get_services_front()
    {
        $result = DB::get('services');
        return $result;
    }


    public function create_service($objectSave)
    {
        $rowCreated = Service::create($objectSave);
        $response = Service::where('id',$rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_service($id, $objectSave)
    {
        $update = Service::find($id)->update($objectSave);
        $response = Service::where('id',$id)->first();
        return $response;
    }

    public function delete_service($id)
    {
        $response = Service::find($id)->delete();

        return $response;
    }
}
