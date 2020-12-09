<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TypePasse;

class TypePasseController extends Controller
{
    public function get_type_passes(Request $request)
    {
        $type_passe = new TypePasse; 
        $type_passes = $type_passe->get_type_passes();
        
        return response()->json([
            'data' => $type_passes
        ]);
    }

    public function save_type_passe(Request $request)
    {
        $type_passe = new TypePasse; 
        $id = $request->input('id');
        $name = $request->input('name');
        $identifier = ($id != 'null') ? $request->input('identifier') : strtoupper(bin2hex(random_bytes(5)));
        $benefeats = $request->input('benefeats');
        $infinite = $request->input('infinite');
        $quantity_sessions = $request->input('quantity_sessions');
        $duration_days = $request->input('duration_days');
        $flexibility = $request->input('flexibility');
        $limit_days_activate = $request->input('limit_days_activate');
        $one_per_user = $request->input('one_per_user');
        $only_first_fee = $request->input('only_first_fee');
        $quantity_fee = $request->input('quantity_fee');
        $month = $request->input('month');
        
 
        $objectSave = [
            'name' => $name,
            'identifier' => $identifier,
            'benefeats' => $benefeats,
            'infinite' => $infinite,
            'quantity_sessions' => $quantity_sessions,
            'duration_days' => $duration_days,
            'flexibility' => $flexibility,
            'limit_days_activate' => $limit_days_activate,
            'one_per_user' => $one_per_user,
            'only_first_fee' => $only_first_fee,
            'quantity_fee' => $quantity_fee,
            'month' => $month
        ];

        if($id != 'null'){
            
            $response = $type_passe->update_type_passe($id, $objectSave);
        }else{
            $type_passe_id = $type_passe->create_type_passe($objectSave);
        }

        $data = $type_passe->get_type_passes();
        
        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }
    
  
    public function delete_type_passe(Request $request)
    {
        $type_passe = new TypePasse; 
        $id = $request->input('id');
        $response = $type_passe->delete_type_passe($id);
        $data = $type_passe->get_passes();
        
        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
