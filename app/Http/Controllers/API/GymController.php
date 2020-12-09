<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\User;

class GymController extends Controller
{
    public function get_gyms(Request $request)
    {
        $gym = new Gym; 
        $response = $gym->get_gyms();
        
        return response()->json([
            "data" => $response
        ]);
    }

    public function get_gym_data(Request $request)
    {
        $gym = new Gym; 
        $user = new User;
        $id = $request->input('id');
        $response = $gym->get_gym_data($id);
        $users = $user->get_gym_admin_users($id);
        
        return response()->json([
            "data" => $response,
            "users" => $users
        ]);
    }

    

    public function save_gym(Request $request)
    {
        $gym = new Gym; 
        $gym_user = new User;
        $id = $request->input('id');
        $type_business = $request->input('type_business');
        $user_id = $request->input('user_id');
        $commercial_name= $request->input('commercial_name');
        $ruc = $request->input('ruc');
        $email= $request->input('email');
        $password = $request->input('password');
        $url_file = $request->input('url_file');
        $resume = $request->input('resume');
 
        $objectSave = [
            'type_business' => $type_business,
            'commercial_name' => $commercial_name,
            'ruc' => $ruc,
            'email' => $email,
            'main_image' => $url_file,
            'resume' => $resume
        ];

        if($id != 'null'){
            
            $response = $gym->update_gym($id, $objectSave);
            $objectUserSave = [
                'email' => $email,
            ];

            $response = $gym_user->update_gym_user($user_id, $objectUserSave);
        }else{
            $gym_id = $gym->create_gym($objectSave);
            $objectUserSave = [
                'gym_id' => $gym_id,
                'name' => 'Default name',
                'email' => $email,
                'password' => bcrypt($password),
                'role_id' => '2',
                'lead' => '1'
            ];
            $response = $gym_user->create_gym_user($objectUserSave);
        }

        $data = $gym->get_gyms();
        
        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }

    public function save_gym_data(Request $request)
    {
        $gym = new Gym; 
        $id = $request->input('id');
        $active = $request->input('active');
        $commercial_name= $request->input('commercial_name');
        $ruc = $request->input('ruc');
        $email= $request->input('email');
        $phone = $request->input('phone');
        $mobile = $request->input('mobile');
        $description = $request->input('description');
        $main_image = $request->input('main_image');
        $type_business = $request->input('type_business');
        
        $objectSave = [
            'active' => $active,
            'commercial_name' => $commercial_name,
            'ruc' => $ruc,
            'email' => $email,
            'phone' => $phone,
            'mobile' => $mobile,
            'description' => $description,
            'main_image' => $main_image,
            'type_business' => $type_business
        ];
            
        $response = $gym->update_gym($id, $objectSave);
        
        return response()->json([
            "error" => "",
            "response" => $response,
        ]);
    }

    public function delete_gym(Request $request)
    {
        $gym = new Gym; 
        $id = $request->input('id');
        $response = $gym->delete_gym($id);
        $data = $gym->get_gyms();
        
        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }
}
