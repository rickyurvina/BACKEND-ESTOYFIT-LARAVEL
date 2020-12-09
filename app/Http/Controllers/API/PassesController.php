<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Passe;
use App\Models\GymBranch;
use App\User;
use Carbon\Carbon;

class PassesController extends Controller
{
    public function get_passes(Request $request)
    {
        $passe = new Passe; 
        $gym_branch = new GymBranch; 
        $passes = $passe->get_passes();
        $gym_branchs = $gym_branch->get_gym_all_branchs();
        
        return response()->json([
            'data' => $passes,
            'gym_branchs' => $gym_branchs
        ]);
    }

    public function get_gym_branchs_by_filter_front(Request $request)
    {
        $passe = new Passe; 
        $response = $passe->get_gym_branchs_by_filter_front($filter_field, $text);

        // $response = $request;
        
        return response()->json([
            "data" => $response
        ]);
    }

    public function get_hot_deal_passes(Request $request, $city)
    {
        $passe = new Passe; 
        $passes = $passe->get_hot_deal_passes($city);
        
        return response()->json([
            'data' => $passes,
        ]);
    }


    

    public function save_passe(Request $request)
    {
        $passe = new Passe; 
        $id = $request->input('id');
        $branch_id = $request->input('branch_id');
        $name = $request->input('name');
        $description = $request->input('description');
        $conditions = $request->input('conditions');
        $original_price = $request->input('original_price');
        $discount = $request->input('discount');
        $type_discount = $request->input('type_discount');
        $discount_value = $request->input('disc');
        $train_now_price = $request->input('train_now_price');
        $days_for_validate = $request->input('days_for_validate');
        $expiration_date = Carbon::parse($request->input('expiration_date'));
        $color = $request->input('color');
        $sort = $request->input('sort');
        $n_avaible = $request->input('n_avaible');
        $valid_from = Carbon::parse($request->input('valid_from'));
        $valid_to = Carbon::parse($request->input('valid_to'));
        $train_now_black = $request->input('train_now_black');
        $hot_deal = $request->input('hot_deal');
        $for_turist = $request->input('for_turist');
        $type = $request->input('type');
        $category = $request->input('category');
        $commission = $request->input('commission');
        $commission_fix_price = $request->input('commission_fix_price');
        $active = $request->input('active');
 
        $objectSave = [
            'branch_id' => $branch_id,
            'name' => $name,
            'description' => $description,
            'conditions' => $conditions,
            'original_price' => $original_price,
            'discount' => $discount,
            'type_discount' => $type_discount,
            'discount_value' => $discount_value,
            'train_now_price' => $train_now_price,
            'days_for_validate' => $days_for_validate,
            'expiration_date' => $expiration_date,
            'color' => $color,
            'sort' => $sort,
            'n_avaible' => $n_avaible,
            'valid_from' => $valid_from,
            'valid_to' => $valid_to,
            'train_now_black' => $train_now_black,
            'hot_deal' => $hot_deal,
            'for_turist' => $for_turist,
            'type' => $type,
            'category' => $category,
            'commission' => $commission,
            'commission_fix_price' => $commission_fix_price,
            'active' => $active,
        ];

        if($id != 'null'){
            
            $response = $passe->update_passe($id, $objectSave);
        }else{
            $passe_id = $passe->create_passe($objectSave);
        }

        $data = $passe->get_passes();
        
        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }
    
    public function active_passe(Request $request)
    {
        $passe = new Passe; 
        $id = $request->input('id');
        $action = $request->input('action');
        $response = $passe->active_passe($id, $action);
        
        return response()->json([
            "error" => "",
            "response" => $response
        ]);
    }

    public function delete_passe(Request $request)
    {
        $passe = new Passe; 
        $id = $request->input('id');
        $response = $passe->delete_passe($id);
        $data = $passe->get_passes();
        
        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
    
     public function get_passes_gym_branch_validated_front(Request $request, $id) {
        $passe = new Passe;
        $passes = $passe->get_passes_gym_branch_validated_front($id);

        return response()->json([
                    'passes' => $passes,
        ]);
    }

}
