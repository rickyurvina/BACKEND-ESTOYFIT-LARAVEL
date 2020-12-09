<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tags;

class TagController extends Controller
{
    //
    public function get_tags(Request $request)
    {
        $tag = new Tag;
        $tags = $tag->get_tags();

        return response()->json([
            'data' => $tags,
        ]);
    }

    public function save_tag(Request $request)
    {
        $tag = new Tag;
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');

        $objectSave = [
            'name' => $name,
            'description' => $description,
        ];

        if($id != 'null'){

            $response = $tag->update_tag($id, $objectSave);
        }else{
            $tag_id = $tag->create_tag($objectSave);
        }

        $data = $tag->get_tags();

        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }

    public function delete_tag(Request $request)
    {
        $tag = new Tag;
        $id = $request->input('id');
        $response = $tag->delete_tag($id);
        $data = $tag->get_tags();

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
