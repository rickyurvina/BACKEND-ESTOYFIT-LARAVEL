<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCardToken extends Model
{
    protected $table = 'user_card_token';
    protected $fillable = [
        'id', 'user_id', 'document', 'number', 'card_token', 'card_number', 'authorization_code', 'reference', 'client_id', 'message', 'card_brand', 'card_holder', 'ip_address', 'object_data_transaction'
    ];

    public function get_cards_by_user($user_id)
    {
        $result = UserCardToken::where('user_id',$user_id)->select('id','number','card_brand')->get();
        return $result;
    }

    public function get_card_by_id($id)
    {
        $result = UserCardToken::where('id',$id)->select('card_token')->first();
        return $result;
    }

    public function save_card_token($objectToDb)
    {
        $result = UserCardToken::create($objectToDb);
        return $result;
    }
}
