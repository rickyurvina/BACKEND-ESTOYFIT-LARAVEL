<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'id',
        'name',
        'description',
        'main_image',
    ];

    public function get_categories()
    {
        $result = DB::table('categories')
        // ->where('categories.gym_id',$id)
        ->get();
        return $result;
    }

    public function get_category_data($id)
    {
        $result = Category::where('id',$id)->first();
        return $result;
    }


    public function get_categories_front()
    {
        $result = DB::get('categories');
        return $result;
    }


    public function create_category($objectSave)
    {
       $rowCreated = Category::create($objectSave);
       $response = Category::where('id',$rowCreated->id)->first();
       return $rowCreated->id;
    }

    public function update_category($id, $objectSave)
    {
        $update = Category::find($id)->update($objectSave);
        $response = Category::where('id',$id)->first();
        return $response;
    }

    public function delete_Category($id)
    {
        $response = Category::find($id)->delete();
        return $response;
    }
}
