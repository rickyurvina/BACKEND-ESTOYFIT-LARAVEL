<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

use App\Models\Vacant;


class VacantController extends Controller
{
    public function get_actives_vacancies(){
        
        $not_found = [
            'message'=>'Not Found'
        ];

        $vacant = new Vacant;
        $vacant = Vacant::where('is_active',1)->get();
        if(is_null($vacant)){
            return response()->json($not_found,404);
        }
        return response()->json($vacant,200);
    }

    public function get_all_vacancies(){

        $not_found = [
            'message'=>'Not Found'
        ];

        $response = Vacant::all();

        if(is_null($response)){
            return response()->json($not_found,404);
        }
        return response()->json($response,200);
    }

    public function set_vacant(Request $request)
    {
        $vacant = $request->json()->all();
        $rules = [
            'title'=>'required',
            'description'=>'required',
            'contact'=>'required',
            'is_active'=>'required'
        ];

        $validation = Validator::make($vacant,$rules);

        if($validation->fails()){
            return response()->json($validation->errors(),404);
        }

        $vacant = Vacant::create($vacant);
        return response()->json($vacant,201);

    }

    public function update_vacant(Request $request,$id){
        $vacant = Vacant::find($id);
        $not_found = [
            'message'=>'Not Found'
        ];
        if(is_null($vacant)){
            return response()->json($not_found,404);
        }
        $vacant->update($request->json()->all());
        return response()->json($vacant,200);
    }

    public function active_vacant($id,$action){
        $vacant = Vacant::find($id)->update(['is_active'=>$action]);

        return response()->json($vacant,200);
    }

    public function delete_vacant($id){
        $vacant = Vacant::find($id);
        $not_found = [
            'message'=>'Not Found'
        ];

        if(is_null($vacant)){
            return response()->json($not_found,404);
        }

        $vacant->delete();
        return response()->json('Deleted',201);
    }
}
