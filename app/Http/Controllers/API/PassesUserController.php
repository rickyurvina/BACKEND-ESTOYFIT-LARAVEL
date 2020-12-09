<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PasseUser;
use App\User;
use Carbon\Carbon;

class PassesUserController extends Controller {

    public function get_user_passes(Request $request, $id_user, $limit, $offset) {
        $passeuser = new PasseUser;
        $passesuser = $passeuser->get_user_passes($id_user, $limit, $offset);

        return response()->json([
                    'data' => $passesuser,
        ]);
    }

    public function save_user_passe(Request $request) {
        $passeuser = new PasseUser;
        $id_pass = $request->input('id_pass');
        $id_user = $request->input('id_user');
        $amount = $request->input('amount');
        $date = date('Y-m-d');
        $objectSave = [
            'id_pass' => $id_pass,
            'id_user' => $id_user,
            'amount' => $amount,
            'created_at' => $date,
        ];


        $passe_user_id = $passeuser->create_passe_user($objectSave);


        return response()->json([
                    "error" => "",
                    "passe_user_id" => $passe_user_id
        ]);
    }

    public function filter_passes(Request $request) {
        $passeuser = new PasseUser;
        $id_pass = $request->input('id_pass');
        $id_user = $request->input('id_user');
        $amount = $request->input('amount');
        $date = date('Y-m-d');
        $objectSave = [
            'id_pass' => $id_pass,
            'id_user' => $id_user,
            'amount' => $amount,
            'created_at' => $date,
        ];
        $passe_user_id = $passeuser->create_passe_user($objectSave);
        return response()->json([
                    "error" => "",
                    "passe_user_id" => $passe_user_id
        ]);
    }

}
