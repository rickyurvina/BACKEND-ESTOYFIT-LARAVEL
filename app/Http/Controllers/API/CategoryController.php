<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function get_categories(Request $request)
    {
        $category = new Category;
        $categories = $category->get_categories();

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function save_category(Request $request)
    {
        $category = new Category;
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

            $response = $category->update_category($id, $objectSave);
        }else{
            $category_id = $category->create_category($objectSave);
        }

        $data = $category->get_categories();

        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }

    public function delete_category(Request $request)
    {
        $category = new Category;
        $id = $request->input('id');
        $response = $category->delete_category($id);
        $data = $category->get_categories();

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
