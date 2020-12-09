<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PasseUser extends Model {

    protected $table = 'passes_user';
    protected $fillable = [
        'id',
        'passe_id',
        'user_id',
        'amount',
        'created_at',
        'updated_at',
        'active'
    ];

    public function get_user_passes($id_user, $limit, $offset) {
        $result = DB::table('passes_user')
                ->leftJoin('passes', 'passes_user.id_pass', '=', 'passes.id')
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->leftJoin('gym_branch', 'passes.branch_id', '=', 'gym_branch.id')
                ->leftJoin('cities', 'gym_branch.city', '=', 'cities.id')
                ->leftJoin('gym', 'gym_branch.gym_id', '=', 'gym.id')
                ->where('user_id', $id_user)
                ->select('passes_user.*', 'passes.*', 'type_passes.name as typeName', 'categories.name as categoryName', 'cities.name as cityName', 'gym_branch.commercial_name as gymName')
                ->skip($offset)
                ->take($limit)
                ->get();
        return $result;
    }

    public function get_user_passe_data($id) {
        $result = PasseUser::where('id', $id)->first();
        return $result;
    }

    public function create_passe_user($objectSave) {
        $rowCreated = PasseUser::create($objectSave);
        $response = PasseUser::where('id', $rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function  get_passes_user_front($id_user){
//        return "llego hasta aqui";
        $result = DB::table('passes_user')
            ->join('passes','passes.id','=','passes_user.passe_id')
            ->join('users','users.id','=','passes_user.user_id')
            ->join('gym_branch','gym_branch.id','passes.branch_id')
            ->where('user_id',$id_user)
            ->select('passes.name as passe_name','users.name as name_user','gym_branch.commercial_name as gym_name','passes_user.amount','passes_user.created_at','passes_user.active')
            ->orderBy('created_at','DESC')
            ->get();
        return $result;

    }

}
