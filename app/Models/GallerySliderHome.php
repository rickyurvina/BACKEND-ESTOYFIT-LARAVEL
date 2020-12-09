<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GallerySliderHome extends Model
{
    //
    protected $table = 'sliderhome';
    protected $fillable = [
        'id',
        'url',
        'title',
        'subtitle'
    ];


    public function get_sliders_home()
    {
//        $result = DB::table('sliderhome')
//            // ->where('sliders_home.gym_id',$id)
//            ->get();
        $result=GallerySliderHome::get();
        return $result;
    }

    public function get_slider_home_data($id)
    {
        $result = GallerySliderHome::where('id',$id)->first();
        return $result;
    }


    public function get_sliders_home_front()
    {
        $result = DB::get('sliders_home');
        return $result;
    }


    public function create_slider_home($objectSave)
    {
        $rowCreated = GallerySliderHome::create($objectSave);
        $response = GallerySliderHome::where('id',$rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_slider_home($id, $objectSave)
    {
        $update = GallerySliderHome::find($id)->update($objectSave);
        $response = GallerySliderHome::where('id',$id)->first();
        return $response;
    }

    public function delete_slider_home($id)
    {
        $response = GallerySliderHome::find($id)->delete();
        return $response;
    }
}
