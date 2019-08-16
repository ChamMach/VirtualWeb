<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;
use App\Classes\SocketHelper;
use Session;
use Illuminate\Support\Facades\Redirect;
use Log;

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
     * Affichage de l'index
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

        //Récupère tous les utilisateurs
        $users = DB::table('users')
        ->select('id', 'nom', 'prenom', 'email')
        ->orderBy('id')
        ->get();

        //Récupère nombre de VM
        $vmTotal = DB::table('vm')->count();

        $userData['users'] = array(
            'data' => $users,
            'error' => false
        );

        //Data pour le dashboard
        $userData['dashboard'] = array(
            'utilisateurs' => $users->count(),
            'vm' => $vmTotal
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

    /**
     * Création d'un utilisateurs
     * @param  Request $request Données du formulaire
     * @return Array            Erreur + message ou succes
     */
    public function createUser(Request $request)
    {
        $return = array(
            'erreur' => true
        );

        //On vérifie avant si l'utilisateur n'existe pas déjà
        $userExist = DB::table('users')
        ->where('email', $request->get('email'))
        ->get();
        //Si ce n'est pas le cas, on peut créer un utilisateur
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
            //On le sauvegarde en base
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

    /**
     * Modification d'un utilisateurs
     * @param  Request $request Données du formulaire
     * @return Array            Erreur + message ou succes
     */
    public function editUser(Request $request)
    {
        $return = array(
            'erreur' => true
        );

        //On vérifie avant si l'utilisateur n'existe pas déjà
        $userExist = DB::table('users')
        ->where('email', $request->get('email'))
        ->get();
        //Si ce n'est pas le cas, on peut créer un utilisateur
        if ($userExist->count() == 1) {
            //Si il y a un nouveau mot de passe dans le formulaire, sinon on garde le même
            if (!is_null($request->get('password'))) {
                $modif = DB::table('users')->where('id', $request->get('id'))
                ->update([
                    'nom' => $request->get('nom'),
                    'prenom' => $request->get('prenom'),
                    'email' => $request->get('email'),
                    'password' => \Hash::make($request->get('password')),
                    'status' => $request->get('status'),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);
            } else {
                $modif = DB::table('users')->where('id', $request->get('id'))
                ->update([
                    'nom' => $request->get('nom'),
                    'prenom' => $request->get('prenom'),
                    'email' => $request->get('email'),
                    'status' => $request->get('status'),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);
            }
            //On vérifie le retour
            if (!is_null($modif)) {
                $return['erreur'] = false;
                $return['message'] = 'Utilisateur modifié en base';
            } else {
                $return['message'] = 'Impossible de modifier l\'utilisateur';
            }
        } else {
            $return['message'] = 'L\'utilisateur n\'existe pas';
        }
        return $return;
    }

    /**
     * Suppression d'un utilisateurs
     * @param  Request $request Id de l'utilisateur à Supprimer
     * @return Array            Erreur + message ou message de succès
     */
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
    
    /**
     * Gère la déconnexion d'un utilisateur
     * @return Route Redirige vers l'accueil
     */
    public function deconnexion()
    {
        $user = Auth::user();
        DB::table('historique')->insert(
            ['id_user' => $user->id, 'date' => date("Y-m-d H:i:s"), 'action' => 2]
        );
        Auth::logout();
        Session::flush();
        return Redirect::to('/');
    }
}
