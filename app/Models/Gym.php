<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gym extends Model
{
    protected $table = 'gym';
    protected $fillable = ['id','active','type_business','commercial_name','description','resume','ruc','email','phone','mobile','main_image'];

    public function get_gyms()
    {
        $result = DB::table('gym')
        ->where('users.role_id','2')
        ->where('users.lead','1')
        ->leftJoin('users', 'users.gym_id', '=', 'gym.id')
        ->select('gym.*','users.email as userEmail','users.id as userId')
        ->get();
        return $result;
    }

    public function get_gym_data($id)
    {
        $result = Gym::where('id',$id)->first();
        return $result;
    }
    

    public function get_gyms_front()
    {
        $result = DB::get('gym');
        return $result;
    }
    
    
    public function create_gym($objectSave)
    {
       $rowCreated = Gym::create($objectSave);
       $response = Gym::where('id',$rowCreated->id)->first();
       return $rowCreated->id;
    }

    public function update_gym($id, $objectSave)
    {
        $update = Gym::find($id)->update($objectSave);
        $response = Gym::where('id',$id)->first();
        return $response;
    }

    public function delete_gym($id)
    {
        $response = Gym::find($id)->delete();
        
        return $response;
    }
}
