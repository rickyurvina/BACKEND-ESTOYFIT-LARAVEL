<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypePasse extends Model
{
    protected $table = 'type_passes';
    protected $fillable = [
        'id',
        'name',
        'identifier',
        'benefeats',
        'infinite',
        'quantity_sessions',
        'duration_days', 
        'flexibility',
        'limit_days_activate', 
        'one_per_user',
        'only_first_fee', 
        'quantity_fee',
        'month'
    ];

    public function get_type_passes()
    {
        $result = DB::table('type_passes')->get();
        return $result;
    }

    public function get_type_passe_data($id)
    {
        $result = TypePasse::where('id',$id)->first();
        return $result;
    }
    

    public function create_type_passe($objectSave)
    {
       $rowCreated = TypePasse::create($objectSave);
       $response = TypePasse::where('id',$rowCreated->id)->first();
       return $rowCreated->id;
    }

    public function update_type_passe($id, $objectSave)
    {
        $update = TypePasse::find($id)->update($objectSave);
        $response = TypePasse::where('id',$id)->first();
        return $response;
    }

    public function delete_type_passe($id)
    {
        $response = TypePasse::find($id)->delete();
        
        return $response;
    }

}
