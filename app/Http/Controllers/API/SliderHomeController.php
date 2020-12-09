<?php

namespace App\Http\Controllers\API;

use App\Models\GallerySliderHome;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderHomeController extends Controller
{
    //
    public function get_sliders_home(Request $request)
    {
        $slider_home = new GallerySliderHome;
        $sliders_home = $slider_home->get_sliders_home();
//        return "llego hasta aqui...";
//$sliders_home="llego hastaaqui";
        return response()->json([
            'data' => $sliders_home,
        ]);
    }

    public function save_slider_home(Request $request)
    {
        $slider_home = new GallerySliderHome;
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

            $response = $slider_home->update_slider_home($id, $objectSave);
        }else{
            $slider_home_id = $slider_home->create_slider_home($objectSave);
        }

        $data = $slider_home->get_sliders_home();

        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }

    public function delete_slider_home(Request $request)
    {
        $slider_home = new GallerySliderHome;
        $id = $request->input('id');
        $response = $slider_home->delete_slider_home($id);
        $data = $slider_home->get_sliders_home();

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
