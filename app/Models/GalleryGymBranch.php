<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryGymBranch extends Model
{
    protected $table = 'gallery_gym_branch';
    protected $fillable = [
        'id',
        'branch_id',
        'url'
    ];


    public function get_gallery_gym_branch($id)
    {
        $response = GalleryGymBranch::where('branch_id',$id)->get();
        return $response;
    }

    public function update_gallery_gym_branch($id, $arr_objects)
    {
        foreach($arr_objects as $obj){
        // for($i=0; $i<sizeof($arr_objects); $i++){
            if($obj->removed == '1'){
                GalleryGymBranch::find($obj->id)->delete();
            }else{
                if($obj->id == null){
                    $objectToSave = [
                        'branch_id' => $id,
                        'url' => $obj->url
                    ];
                    GalleryGymBranch::create($objectToSave);
                }
            }
        }
        // $update = GymBranch::find($id)->update($objectSave);
        // $response = GymBranch::where('id',$id)->first();
        // return $response;
    }
}
