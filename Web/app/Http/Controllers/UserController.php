<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the currently authenticated user...
        $user = Auth::user();
        $status = array('user', 'admin');
        $nom = $user->prenom . ' ' . $user->nom;
        $userData = array(
            'nom' => $nom,
            'status' => $status[$user->status],
            'verified' => Auth::check(),
        );
        return view('home', [
            'dataToShow' => json_encode($userData)
        ]);
    }
    
    /**
     * Récupération des données utilisateurs
     * @return array Liste de tous les utilisateurs
     */
    public function getUsers()
    {
        $currentUser = Auth::user();
        
        //If user is admin
        if ($currentUser->status == 1) {
            $users = DB::table('users')
            ->select('id', 'nom', 'prenom', 'email')
            ->orderBy('id')
            ->get();
            $return = array(
                'users' => $users,
                'error' => false
            );
        } else {
            $return = array(
                'error' => true
            );
        }
        return $return;
    }
    
    public function createUser(Request $request)
    {
        $return = array(
            'erreur' => true
        );
        $currentUser = Auth::user();
        if ($currentUser->status == 1)  {
            $userExist = DB::table('users')
            ->where('email', $request->get('email'))
            ->get();
            if (!$userExist) {
                $user = new User([
                    'nom' => $request->get('nom'),
                    'prenom' => $request->get('prenom'),
                    'email' => $request->get('email'),
                    'password' => \Hash::make($request->get('password')),
                    'status' => $request->get('status'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);
                $saved = $user->save();
                if ($saved) {
                    $return = array(
                        'erreur' => false
                    );
                }
            }
        }

        return $return;
    }
}
