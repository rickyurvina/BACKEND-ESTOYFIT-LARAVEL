<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Publicity;

class PublicityController extends Controller
{
    //
    public function get_publicity(Request $request)
    {
        $publicity = new Publicity;
        $publicit = $publicity->get_publicity();
//        dd($publicit);

        return response()->json([
            'data' => $publicit,
        ]);
    }

    public function save_publicity(Request $request)
    {
        $publicity = new Publicity;
        $id = $request->input('id');
        $name = $request->input('name');
        $title = $request->input('title');
        $subtitle = $request->input('subtitle');
        $url_file = $request->input('url');

        $objectSave = [
            'name' => $name,
            'title' => $title,
            'url'=>$url_file,
            'subtitle'=>$subtitle
        ];

        if($id != 'null'){

            $response = $publicity->update_publicity($id, $objectSave);
        }else{
            $publicity_id = $publicity->create_publicity($objectSave);
        }

        $data = $publicity->get_publicity();

        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }

    public function delete_publicity(Request $request)
    {
        $publicity = new Publicity;
        $id = $request->input('id');
        $response = $publicity->delete_publicity($id);
        $data = $publicity->get_publicity();

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
