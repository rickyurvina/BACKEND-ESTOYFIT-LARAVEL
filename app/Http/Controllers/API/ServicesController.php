<?php

namespace App\Http\Controllers\API;

use App\Mail\OrderMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ServicesController extends Controller
{

    
    public function contact(Request $request)
    {
        try {
            $data = [
                'code' => $request->input('code'),
            ];

            $subject = "Recovery password";
            $for = "josegonzalez19891989@gmail.com";

            Mail::send('recovery_password_mail', ["object" => $data], function ($msj) use ($subject, $for) {
                $msj->from("josegonzalez19891989@gmail.com", "Nombre Emisor Correo");
                $msj->subject($subject);
                $msj->to($for);
            });
            return "mensaje exitosoo";
        } catch (\Throwable $e) {
            return $e;
        }
    }
}
