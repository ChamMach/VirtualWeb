<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function doLogin(Request $request)
    {
        $auth = false;
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $auth = true; // Success
        }

        //Initialise les valeurs à false
        $return['succes'] = false;
        $return['erreur'] = false;

        if ($auth == true) {
            $return['succes'] == true;
            $return['message'] = "Connexion réussie";
        } else {
            $return['erreur'] == true;
            $return['message'] = "Votre email ou mot de passe est incorrecte";
        }
        return json_encode($return);
    }
}
