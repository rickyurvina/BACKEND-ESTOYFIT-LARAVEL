<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;

class AmenityController extends Controller
{
    //
    public function get_amenities(Request $request)
    {
        $amenity = new Amenity;
        $amenities = $amenity->get_amenities();

        return response()->json([
            'data' => $amenities,
        ]);
    }

    public function save_amenity(Request $request)
    {
        $amenity = new Amenity;
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $url_file = $request->input('url_file');

        $objectSave = [
            'name' => $name,
            'description' => $description,
            'main_image'=>$url_file
        ];

        if($id != 'null'){

            $response = $amenity->update_amenity($id, $objectSave);
        }else{
            $amenity_id = $amenity->create_amenity($objectSave);
        }

        $data = $amenity->get_amenities();

        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }

    public function delete_amenity(Request $request)
    {
        $amenity = new Amenity;
        $id = $request->input('id');
        $response = $amenity->delete_amenity($id);
        $data = $amenity->get_amenities();

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
