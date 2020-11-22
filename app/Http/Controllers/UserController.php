<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\Models\User;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function register(Request $request){

        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $input = $request->only('name', 'email', 'password');

        try{
            $user = new User;
            $user->name = $input['name'];
            $user->email = $input['email'];
            $password = $input['password'];

            $user->password = app('hash')->make($password);

            if($user->save() ){
                $code = 200;
                $output = [
                    'user' => $user,
                    'code' => $code,
                    'message' => 'Uzytkownik pomyslnie dodany.'
                ];
            } else {
                $code = 500;
                $output = [
                    'code' => $code,
                    'message' => 'blad przy dodawaniu uzytkownika.'
                ];
            }
            
        } catch (Exception $e) {
            $code = 500;
            $output = [
                'code' => $code,
                'message' => "blad przy dodwaniu uzytkownika."
            ];
        }

        return response()->json($output, $code);
    }

    public function login(Request $request) {

        dd($request);
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $input = $request->only('email', 'password');

        If( !$authorized = Auth::attempt($input)){

            $code = 401;
            $output = [
                'code' => $code,
                'message' => "Uzytkownik nie posiada autoryzacji"
            ];

        } else {
            $this->respondWithToken($authorized);
        }

    }
}

