<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SuggestionTag;

class ServiceController extends Controller
{
    public function get_services(Request $request)
    {
        $service = new Service; 
        $services = $service->get_services();
        
        return response()->json([
            'data' => $services,
        ]);
    }

    public function save_service(Request $request)
    {
        $service = new Service;
        $sugestion_tag = new SuggestionTag;
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
 
        $objectSave = [
            'name' => $name,
            'description' => $description,
        ];

        if($id != 'null'){
            
            $response = $service->update_service($id, $objectSave);
        }else{
            $service_id = $service->create_service($objectSave);
        }

        $id_row = ($id != 'null') ? $id : $service_id;
        $objectSaveSuggestionTag = [
            'id_row' => $id_row,
            'type' => '2',
            'name'=> $name,
            'categories' => json_encode([0,3,5])
        ];

        $resp_suggestion_tag = $sugestion_tag->save_sugestion_tag($objectSaveSuggestionTag);

        $data = $service->get_services();
        
        return response()->json([
            "error" => "",
            "data" => $data
        ]);
    }

    public function delete_service(Request $request)
    {
        $service = new Service; 
        $id = $request->input('id');
        $response = $service->delete_service($id);
        $data = $service->get_services();
        
        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
