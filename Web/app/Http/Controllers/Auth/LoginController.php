<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

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
    protected $redirectTo = '/accueil';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Gère la connexion de l'utilisateur
     * @param  Request $request Données du formulaire
     * @return array           Erreur ou suscès
     */
    public function connexion (Request $request)
    {
        $auth = false;
        //On récupère les données du formulaire
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $auth = true; // Success
        }

        //Initialise les valeurs à false
        $return['succes'] = false;
        $return['erreur'] = false;

        //Si on a une erreur on ajoute le message
        if ($auth == false) {
            $return['erreur'] == true;
            $return['message'] = "Votre email ou mot de passe est incorrecte";
        } else {
            //On logue la connexion de l'utilisateur en BDD
            $user = Auth::user();
            DB::table('historique')->insert(
                ['id_user' => $user->id, 'date' => date("Y-m-d H:i:s"), 'action' => 1]
            );
            $return['succes'] = true;
            if (Auth::user()->status == 1) {
                $return['status'] = 'admin';
            } else {
                $return['status'] = 'user';
            }
        }
        return json_encode($return);
    }

    public function loginPage()
    {
        $userData = array(
            'status' => 'user',
            'verified' => false,
        );
        return view('connexion', [
            'dataToShow' => json_encode($userData)
        ]);
    }

    public function deconnexion()
    {
        Auth::logout();
        return redirect('/');
    }
}
