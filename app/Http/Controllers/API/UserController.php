<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\City;
use App\Models\PasseUser;
use App\Models\Provinces;
use http\Message;
use Illuminate\Foundation\Console\PackageDiscoverCommand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\GymBranch;
use Socialite;

class UserController extends Controller {

    // use AuthenticatesUsers;
    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password

     */
    public function signup(Request $request) {
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => '5'
        ]);

        $user->save();
        return response()->json([
                    'message' => 'Successfully created user!'
                        ], 201);
    }

    public function signupClient(Request $request) {
        $user = new User([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => '5'
        ]);

        $user->save();
        $user->id;
        return $this->login($request);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string'
                    // 'remember_me' => 'boolean'
            ]);

            $credentials = request(['email', 'password']);
            // $credentials['password'] = bcrypt($credentials['password']);

            if (!Auth::attempt($credentials))
                return response()->json([
                    'message' => 'Unauthorized',
                        //'resp' => $credentials
                            ], 401);

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;

            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);

            $token->save();

            $user = $request->user();
            $user_data = $user->data(Auth::id());


        return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                )->toDateTimeString(),
                'userData' => $user_data
        ]);

    } catch (\Throwable $e) {
        return "ERROR" . " " . $e;
    }

    }

    public function loginGoogle(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
                // 'password' => 'required|string'
                // 'remember_me' => 'boolean'
        ]);


        $user_found = User::where('email', $request->input('email'))->first();
        if ($user_found) {
            Auth::login($user_found);
            $token = Auth::user()->createToken('Personal Access Token')->accessToken;
            return response()->json([
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'userData' => $user_found,
            ]);
        } else {
            $user = new User;
            $user->name = $request->input('name');
            $user->role_id = '5';
            $user->email = $request->input('email');
            $user->password = bcrypt($user->name);
            $user->image_profile = $user->image_profile;
            $user->save();
            $user_found = User::where('email', $request->input('email'))->first();
            Auth::login($user_found);
            $token = Auth::user()->createToken('Personal Access Token')->accessToken;

            return response()->json([
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'userData' => $user_found,
            ]);
        }

    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
                    'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request) {

        $user = $request->user();
        $roles_user = $user->roles();

        return response()->json([
                    "userRoles" => $roles_user
        ]);
    }

    public function get_gym_users(Request $request) {
        $gym_user = new User;
        $response = $gym_user->get_gym_users(Auth::user()->gym_id);
        $gym_branch = new GymBranch;
        $branchs = $gym_branch->get_gym_branchs(Auth::user()->gym_id);

        return response()->json([
                    "data" => $response,
                    "branchs" => $branchs
        ]);
    }

    //testing ru

    public function get_gym_user_profile($id) {
        try {
            $gym_user = new User;
            $province= new Provinces;
            $city= new City;
            $category= new Category;
            $response = $gym_user->get_gym_user($id);
            $provinces=$province->get_provinces();
            $cities=$city->get_cities();
            $categories=$category->get_categories();

            return response()->json([
                        "data" => $response,
                'provinces'=>$provinces,
                'cities'=>$cities,
                'categories'=>$categories,
            ]);
        } catch (\Throwable $e) {
            return "ERROR" . " " . $e;
        }
    }

    public function update_user(Request $request, $id) {
        try {

            $gym_user = new User;
            $name = $request->input('name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');
            $resume = $request->input('resume');
            $province = $request->input('province');
            $secondary_street = $request->input('secondary_street');
            $main_street = $request->input('main_street');
            $neighborhood = $request->input('neighborhood');
            $city = $request->input('city');
            $mobile = $request->input('mobile');
            $image_profile = $request->input('image_profile');
            $image_before=$request->input('image_before');
            $image_after=$request->input('image_after');
            $description=$request->input('description');
            $activities=$request->input('activities');
            $request = [
                'name' => $name,
                'last_name' => $last_name,
                'email' => $email,
                'resume' => $resume,
                'province' => $province,
                'secondary_street' => $secondary_street,
                'main_street' => $main_street,
                'neighborhood' => $neighborhood,
                'city' => $city,
                'activities'=>json_encode($activities, JSON_NUMERIC_CHECK),
                'mobile' => $mobile,
                'image_profile' => $image_profile,
                'image_before'=>$image_before,
                'image_after'=>$image_after,
                'description'=>$description,
            ];
            User::find($id)->update($request);
            return response()->json([
                        'data' => $gym_user->find($id)
            ]);
        } catch (\Throwable $e) {
            return "Error: " . $e;
        }
    }

    public function delete_gym_users($id) {
        try {
            User::find($id)->delete();
            return "Eliminado Corecctamente";
        } catch (\Throwable $e) {
            return "Error" . "" . $e;
        }
    }

    //end testing ru

    public function save_gym_user(Request $request) {
        $gym_user = new User;
        $id = $request->input('id');
        $role_id = $request->input('role_id');
        $branch_id = $request->input('branch_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');


        $objectSave = [
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'role_id' => $role_id,
            'gym_id' => Auth::user()->gym_id,
            'branch_id' => $branch_id
        ];

        if ($id != 'null') {
            unset($objectSave['password']);
            $response = $gym_user->update_gym_user($id, $objectSave);
        } else {
            $response = $gym_user->create_gym_user($objectSave);
        }

        $data = $gym_user->get_gym_users(Auth::user()->gym_id);

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data
        ]);
    }

    public function set_user_city(Request $request) {
        $gym_user = new User;
        $id = Auth::user()->id;
        $city = $request->input('city');

        $objectSave = [
            'city' => $city,
        ];

        $response = $gym_user->update_user_city($id, $objectSave);
        $data = $gym_user->get_gym_users(Auth::user()->gym_id);

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data
        ]);
    }

    public function delete_gym_user(Request $request) {
        $gym_user = new User;
        $id = $request->input('id');
        $response = $gym_user->delete_gym_user($id);
        $data = $gym_user->get_gym_users(Auth::user()->gym_id);

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data
        ]);
    }

    public function redirectToProvider() {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback() {
        $user = Socialite::driver('google')->stateless()->user();

        return $user->getName();
    }

    public function get_user_by_id(Request $request, $id) {
        $gym_user = new User;
        $response = $gym_user->get_user_by_id($id);
        return response()->json([
                    "data" => $response,
        ]);
    }

    public function get_users(){
        $user= new User;
        $passes_user=new PasseUser;
        $response= $user->get_users();
//        $passe_user=$passes_user->get_passes_user_front($id_user);
        return response()->json([
            "data" => $response,
//            'passes_user'=>$passe_user
        ]);


    }
    public function get_passes_users($id_user){
        $passes_user=new PasseUser;
        $passe_user=$passes_user->get_passes_user_front($id_user);
        return response()->json([
            'data'=>$passe_user
        ]);


    }

}
