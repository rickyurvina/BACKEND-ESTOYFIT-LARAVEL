<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blog extends Model {

    //
    protected $table = 'blogs';
    protected $fillable = [
        'id',
        'title',
        'description_blog',
        'content',
        'category',
        'is_active',
        'main_image',
        'tags_selected',
    ];

    public function get_blogs() {
        $result = DB::table('blogs')
                // ->where('blogs.gym_id',$id)
                ->get();
        return $result;
    }

    public function get_blog_data($id) {
        $result = Blog::where('id', $id)->first();
        return $result;
    }

    public function get_blogs_front() {
        $result = DB::get('blogs');
        return $result;
    }

    public function create_blog($objectSave) {
        $rowCreated = Blog::create($objectSave);
        $response = Blog::where('id', $rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_blog($id, $objectSave) {

        try {
            Blog::findOrFail($id)->update($objectSave);
            $response = Blog::where('id', $id)->first();
            return $response;
        } catch (\Throwable $e) {
            return "Error" . $e;
        }
    }

    public function delete_Blog($id) {
        $response = Blog::find($id)->delete();

        return $response;
    }

    public function active_blog($id, $action) {
        $update = Blog::find($id)->update(['is_active' => $action]);

        return $update;
    }

    public function get_paginated_blogs($limit, $offset) {
        $result = DB::table('blogs')
                ->select('blogs.*')
                ->skip($offset)
                ->take($limit)
                ->get();
        return $result;
    }

    public function get_total_blogs() {
        $result = DB::table('blogs')->count();
        return $result;
    }

}
