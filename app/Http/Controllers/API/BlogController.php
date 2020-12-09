<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller {

    //functions to front end client
    public function get_actives_blogs() {
        $blog = new Blog;
        $blog = Blog::where('is_active', 1)->get();
        $tags = new Tag;
        $tags = Tag::orderBy('name')->get();

//        return $blog;
        return response()->json([
                    'data' => $blog,
                    'tags' => $tags
        ]);
    }

    public function get_actives_blog() {
        $blog = new Blog;
        $blog = Blog::where('is_active', 1)->get();
        $arr = array();
        $cont = 0;
        foreach ($blog as $bl) {
            $arr[$cont] = $bl->id;
            $cont++;
        }
        $id = $arr[0];
        $blog = Blog::where('id', $id)->get();
        $tags_selected = '';
        foreach ($blog as $bl) {
            $tags_selected = $bl->tags_selected;
        }
        $array = json_decode($tags_selected);
        $tags = new Tag;
        $tags = Tag::where('id', $array[0])->get();
        $cont = 0;
        $names = [];
        foreach ($array as $id) {
            $tags = Tag::where('id', $id)->get();
            foreach ($tags as $tag) {
                $names[$cont] = $tag->name;
            }
            $cont = $cont + 1;
        }
        return response()->json([
                    'data' => $blog,
                    'tags' => $names,
        ]);
    }

    public function get_actives_blog1() {
        $blog = new Blog;
        $blog = Blog::where('is_active', 1)->get();
        $arr = array();
        $cont = 0;
        foreach ($blog as $bl) {
            $arr[$cont] = $bl->id;
            $cont++;
        }
        $id = $arr[1];
        $blog = Blog::where('id', $id)->get();

        $tags_selected = '';
        foreach ($blog as $bl) {
            $tags_selected = $bl->tags_selected;
        }
        $array = json_decode($tags_selected);
        $tags = new Tag;
        $tags = Tag::where('id', $array[0])->get();
        $cont = 0;

        $names = [];

        foreach ($array as $id) {
            $tags = Tag::where('id', $id)->get();
            foreach ($tags as $tag) {
                $names[$cont] = $tag->name;
            }
            $cont = $cont + 1;
        }
        return response()->json([
                    'data' => $blog,
                    'tags' => $names,
        ]);
    }

    public function get_actives_blog2() {
        $blog = new Blog;
        $blog = Blog::where('is_active', 1)->get();
        $arr = array();
        $cont = 0;
        foreach ($blog as $bl) {
            $arr[$cont] = $bl->id;
            $cont++;
        }
        $id = $arr[2];
        $blog = Blog::where('id', $id)->get();
        $tags_selected = '';
        foreach ($blog as $bl) {
            $tags_selected = $bl->tags_selected;
        }
        $array = json_decode($tags_selected);
        $tags = new Tag;
        $tags = Tag::where('id', $array[0])->get();
        $cont = 0;

        $names = [];

        foreach ($array as $id) {
            $tags = Tag::where('id', $id)->get();
            foreach ($tags as $tag) {
                $names[$cont] = $tag->name;
            }
            $cont = $cont + 1;
        }
        return response()->json([
                    'data' => $blog,
                    'tags' => $names,
        ]);
    }

    public function get_actives_blog3() {
        $blog = new Blog;
        $blog = Blog::where('is_active', 1)->get();
        $arr = array();
        $cont = 0;
        foreach ($blog as $bl) {
            $arr[$cont] = $bl->id;
            $cont++;
        }
        $id = $arr[3];
        $blog = Blog::where('id', $id)->get();
        $tags_selected = '';
        foreach ($blog as $bl) {
            $tags_selected = $bl->tags_selected;
        }
        $array = json_decode($tags_selected);
        $tags = new Tag;
        $tags = Tag::where('id', $array[0])->get();
        $cont = 0;

        $names = [];

        foreach ($array as $id) {
            $tags = Tag::where('id', $id)->get();
            foreach ($tags as $tag) {
                $names[$cont] = $tag->name;
            }
            $cont = $cont + 1;
        }
        return response()->json([
                    'data' => $blog,
                    'tags' => $names,
        ]);
        ;
    }

    //end functions to enf front client
    //
    public function get_blogs(Request $request) {
        $blog = new Blog;
        $tag = new Tag;
        $blogs = $blog->get_blogs();
        $tags = $tag->get_tags();
        return response()->json([
                    'data' => $blogs,
                    "tags" => $tags,
        ]);
    }

    public function save_blog(Request $request) {
        $blog = new Blog;
        $id = $request->input('id');
        $title = $request->input('title');
        $description_blog = $request->input('description_blog');
        $content = $request->input('content');
        $category = $request->input('category');
        $is_active = $request->input('is_active');
        $url_file = $request->input('url_file');
        $tags_selected = $request->input('tags_selected');
        $objectSave = [
            'title' => $title,
            'description_blog' => $description_blog,
            'content' => $content,
            'category' => $category,
            'is_active' => $is_active,
            'main_image' => $url_file,
//            'tags_selected'=>json_encode($tags_selected),
            'tags_selected' => json_encode(array_map('intval', explode(',', $tags_selected))),
        ];
        $error = "";
        $actives_count = count(Blog::where('is_active', 1)->get());
        if ($id != 'null') {
//            if(($actives_count>=4)&&($is_active==1)){
//                $error ="Ya existen 4 blogs activos";
//
//            }elseif(($is_active==0)&&($actives_count>4)){
//                //si puede
//                $response = $blog->update_blog($id, $objectSave);
//            }elseif($actives_count<5){
//                $response = $blog->update_blog($id, $objectSave);
//            }
            $response = $blog->update_blog($id, $objectSave);
        } else {
            if (($actives_count == 4) && ($is_active === 1)) {
                $error = "Ya existen 4 blogs activos";
            } elseif (($is_active == 0) && ($actives_count == 4)) {
                $blog_id = $blog->create_blog($objectSave);
            } elseif ($actives_count < 4) {
                $blog_id = $blog->create_blog($objectSave);
            }
        }
        $data = $blog->get_blogs();
        return response()->json([
                    "error" => $error,
                    "data" => $data,
        ]);
    }

    public function delete_blog(Request $request) {
        $blog = new Blog;
        $id = $request->input('id');
        $response = $blog->delete_blog($id);
        $data = $blog->get_blogs();

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data
        ]);
    }

    public function active_blog(Request $request) {
        $blog = new Blog;
        $id = $request->input('id');
        $action = $request->input('action');
        $response = $blog->active_blog($id, $action);

        return response()->json([
                    "error" => "",
                    "response" => $response
        ]);
    }

    public function get_blogs_paginated(Request $request, $limit, $offset) {
        $blog = new Blog;
        $blogs = $blog->get_paginated_blogs($limit, $offset);
        $total = $blog->get_total_blogs();

        return response()->json([
                    "blogs" => $blogs,
                    "total" => $total
        ]);
    }

}
