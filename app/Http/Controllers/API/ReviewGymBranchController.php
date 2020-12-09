<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReviewGymBranch;

class ReviewGymBranchController extends Controller
{
    //
    public function save_review(Request $request) {
       $review = new ReviewGymBranch;
        $branch_id = $request->input('branch_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $rating_value = $request->input('rating_value');
        $title = $request->input('title');
        $content = $request->input('content');
//        $active=$request->input('active');
        $objectSave = [
            'branch_id' => $branch_id,
            'name' => $name,
            'email' => $email,
            'rating_value' => $rating_value,
            'title' => $title,
            'content' => $content,
//            'active'=>$active
        ];
      try{
          $review_id = $review->create_review($objectSave);

      }catch (\Throwable $e){
          return $e;
      }
      return  "Ingresado extiosamente";

    }

    public function active_review(Request $request)
    {
        $passe = new ReviewGymBranch;


        $id = $request->input('id');
        $action = $request->input('action');
//        return $request;
        $response = $passe->active_review($id, $action);

        return response()->json([
            "error" => "",
            "response" => $response
        ]);
    }
}
