<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('isAdmin');
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
        $users = DB::table('users')
        ->select('id', 'nom', 'prenom', 'email')
        ->orderBy('id')
        ->get();
        $userData['users'] = array(
            'data' => $users,
            'error' => false
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

        $users = DB::table('users')
        ->select('id', 'nom', 'prenom', 'email')
        ->orderBy('id')
        ->get();
        $return = array(
            'users' => $users,
            'error' => false
        );

        return $return;
    }


    public function createUser(Request $request)
    {
        $return = array(
            'erreur' => true
        );

        $userExist = DB::table('users')
        ->where('email', $request->get('email'))
        ->get();
        if ($userExist->count() == 0) {
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
                $return['erreur'] = false;
                $return['message'] = 'Utilisateur ajouté en base';
            }
        } else {
            $return['message'] = 'L\'utilisateur existe déjà';
        }
        return $return;
    }

    public function deleteUser(Request $request)
    {
        $return = array(
            'erreur' => true
        );

        $userExist = DB::table('users')
        ->where('id', $request->get('id'))
        ->get();

        if ($userExist->count() !== 0) {
            DB::table('users')->where('id', $request->get('id'))->delete();
            $return['erreur'] = false;
            $return['message'] = 'Utilisateur supprimé de la base';
        } else {
            $return['message'] = 'L\'utilisateur n\'existe pas';
        }
        return $return;
    }
}
