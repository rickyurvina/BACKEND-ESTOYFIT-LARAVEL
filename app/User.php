<?php

namespace App;

use App\Models\Passe;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Role;

class User extends Authenticatable
{

    use HasApiTokens,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email',
        'resume', 'province', 'secondary_street',
        'main_street', 'neighborhood', 'mobile',
        'image_profile', 'city', 'password',
        'current_level_id', 'gym_id', 'branch_id',
        'role_id', 'lead','image_before','image_after','description','activities'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function data($id)
    {
        $user_roles = User::where('users.id', $id)
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('gym', 'users.gym_id', '=', 'gym.id')
            ->leftJoin('gym_branch', 'users.branch_id', '=', 'gym_branch.id')
            ->select('users.*', 'roles.name as roleName', 'users.name as userName', 'gym.commercial_name as gymCName', 'gym_branch.commercial_name as branchCName')->first();
        return $user_roles;
    }

    public function exist_email($email)
    {
        $result = DB::table('users')->where('email', $email)->first();
        return $result;
    }

    public function get_gym_users($id)
    {
        $result = DB::table('users')->where('gym_id', $id)->get();
        return $result;
    }

    public function get_gym_user($id)
    {
        $result = DB::table('users')->where('id', $id)->get();
        return $result;
    }

    public function get_gym_admin_users($id)
    {
        $result = DB::table('users')
            ->where('gym_id', $id)
            ->where('role_id', '2')
            ->get();
        return $result;
    }

    public function get_gym_branch_admin_users($id)
    {
        $result = DB::table('users')
            ->where('branch_id', $id)
            ->where('role_id', '3')
            ->get();
        return $result;
    }

    public function create_gym_user($objectSave)
    {
        $rowCreated = User::create($objectSave);
        $response = User::where('id', $rowCreated->id)->first();
        return $response;
    }

    public function update_gym_user($id, $objectSave)
    {
        $update = User::find($id)->update($objectSave);
        $response = User::where('id', $id)->first();
        return $response;
    }

    //testin ru
    public function update_gym_users($id, $objectSave)
    {
        $update = User::find($id)->update($objectSave);
        $response = User::where('id', $id)->first();
        return $response;
    }

    //end testing ry

    public function update_user_city($id, $objectSave)
    {
        $update = User::find($id)->update($objectSave);
        $response = User::where('id', $id)->first();
        return $response;
    }

    public function delete_gym_user($id)
    {
        $response = User::find($id)->delete();
        return $response;
    }

    public function get_user_by_id($id)
    {
        $result = DB::table('users')->where('id', $id)->get();
        return $result;
    }

    public function get_users(){
        $result = DB::table('users')
            ->join('cities','users.city','=','cities.id')
            ->select('users.id','users.name','users.last_name','users.mobile','users.email','users.created_at','users.image_profile','cities.name as city_name')
            ->get();
        return $result;
    }

}
