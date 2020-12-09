<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReviewGymBranch extends Model {

    //
    protected $table = 'review_gym_branch';
    protected $fillable = [
        'id',
        'branch_id',
        'name',
        'email',
        'rating_value',
        'title',
        'content',
        'active'
    ];

    public function get_review_gym_branch($id) {
        $response = ReviewGymBranch::where('branch_id', $id)
            ->where('active',1)->get();
        $count=count($response);
        $avg=$response->avg('rating_value');
//        return response()->json([
//           'response'=> $response,
//            'avg'=>$avg
//        ]);
        return $response;
    }
    public function get_reviews() {
        $response = ReviewGymBranch::get();
        $result= DB::table('review_gym_branch')
            ->join('gym_branch','gym_branch.id','=','review_gym_branch.branch_id')
            ->select('review_gym_branch.id as id','review_gym_branch.name as name','review_gym_branch.email as email','review_gym_branch.rating_value as rating_value','review_gym_branch.title as title','review_gym_branch.content as content','review_gym_branch.active as active','gym_branch.commercial_name as gym_name')
            ->get();
        return $result;
//        return $response;
    }
    public function delete_review($id) {
        $response = ReviewGymBranch::find($id)->delete();

        return $response;
    }
    public function active_review($id, $action) {
        $update = ReviewGymBranch::find($id)->update(['active' => $action]);

        return $update;
    }

    public function create_review($objectSave) {
        $rowCreated = ReviewGymBranch::create($objectSave);
//        $response = ReviewGymBranch::where('id', $rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_review_gym_branch($id, $arr_objects) {
        foreach ($arr_objects as $obj) {
            // for($i=0; $i<sizeof($arr_objects); $i++){
            if ($obj->removed == '1') {
                ReviewGymBranch::find($obj->id)->delete();
            } else {
                if ($obj->id == null) {
                    $objectToSave = [
                        'branch_id' => $id,
                        'name' => $obj->name,
                        'email' => $obj->email,
                        'rating_value' => $obj->rating_value,
                        'title' => $obj->title,
                        'content' => $obj->content,
//                        'active'=>$obj->active,
                    ];
                    ReviewGymBranch::create($objectToSave);
                }
            }
        }
        // $update = GymBranch::find($id)->update($objectSave);
        // $response = GymBranch::where('id',$id)->first();
        // return $response;
    }

    public function get_average_rating_gym_branch($id) {
        $response = DB::table('review_gym_branch')
                ->where('branch_id', $id)
                ->avg('rating_value');
        $gym_branch = new GymBranch;
        $raiting_value = $response;
        $objectSave = [
            'rating_value' => $raiting_value,
        ];
        $gym_branch->update_gym_branch($id, $objectSave);


        return $response;
    }

}
