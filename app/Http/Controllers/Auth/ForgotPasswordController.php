<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\ForgotRequest;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function forgot(ForgotRequest $request)
    {
        $email = $request->input('email');

        if(User::where('email',$email)->doesntExist()){
            return response([
                'message' => 'User doesn\'t exists!'
            ], 404);
        }

        $token = Str::random(10);

        try{

            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);

            return response([
                'message' => 'Check your email',
                'token' => $token
            ]);

        } catch (\Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        
    }

    public function get_code_recovery(ForgotRequest $request)
    {
        $email = $request->input('email');

        if(User::where('email',$email)->doesntExist()){
            return response([
                'message' => 'User doesn\'t exists!'
            ], 404);
        }

        $token = Str::random(10);
        $code = Str::random(6);

        try{

            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'code' => $code
            ]);

            
            $this->send_code_recovery_mail($code, $email);

            return response([
                'message' => 'Check your email',
                'token' => $token
            ]);

        } catch (\Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        
    }

    public function reset(ResetRequest $request)
    {
        $token = $request->input('token');

        if(!$password_resets = DB::table('password_resets')->where('token', $token)->first()){
            return response([
                'message' => 'Invalid token!',
                'token' => $token
            ], 400);
        }

        if(!$user = User::where('email', $password_resets->email)->first()){
            return response([
                'message' => 'User doesn\'t exists!',
                'user' => $password_resets->email
            ], 404);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response([
            'message' => 'Success'
        ], 200);
        
    }

    public function reset_recovery(ResetRequest $request)
    {
        $token = $request->input('token');
        // $code = $request->input('code');

        // if(!$password_resets = DB::table('password_resets')->where('token', $token)->where('code', $code)->first()){
        //     return response([
        //         'message' => 'Invalid token or code!',
        //         'token' => $token,
        //         'code' => $code
        //     ], 400);
        // }

        // if(!$user = User::where('email', $password_resets->email)->first()){
        //     return response([
        //         'message' => 'User doesn\'t exists!',
        //         'user' => $password_resets->email
        //     ], 404);
        // }

        // $user->password = bcrypt($request->input('password'));
        // $user->save();

        return response([
            'message' => 'Success'
        ], 200);
        
    }

    public function test(ResetRequest $request)
    {
        $token = $request->input('token');
        // $code = $request->input('code');

        // if(!$password_resets = DB::table('password_resets')->where('token', $token)->where('code', $code)->first()){
        //     return response([
        //         'message' => 'Invalid token or code!',
        //         'token' => $token,
        //         'code' => $code
        //     ], 400);
        // }

        // if(!$user = User::where('email', $password_resets->email)->first()){
        //     return response([
        //         'message' => 'User doesn\'t exists!',
        //         'user' => $password_resets->email
        //     ], 404);
        // }

        // $user->password = bcrypt($request->input('password'));
        // $user->save();

        return response([
            'message' => 'Success'
        ], 200);
        
    }

    public function send_code_recovery_mail($code, $email)
    {
        try {
            $data = [
                'code' => $code,
            ];

            $subject = "Recovery password";
            $for = $email;

            Mail::send('recovery_password_mail', ["object" => $data], function ($msj) use ($subject, $for) {
                $msj->from("estoyfituio@gmail.com", "Estoy fit");
                $msj->subject($subject);
                $msj->to($for);
            });
            return "mensaje exitosoo";
        } catch (\Throwable $e) {
            return $e;
        }
    }
}
