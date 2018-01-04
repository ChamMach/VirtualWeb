<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;
use App\Classes\SocketHelper;
use App\VM;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Accès au controlleur uniquement si utilisateur
        $this->middleware('isUser');
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
            'vm' => self::getVM($user->id),
        );
        return view('home', [
            'dataToShow' => json_encode($userData)
        ]);
    }

    /**
     * Récupère les VM de l'utilisateur
     * @return array Tableau contenant les VM
     */
    private function getVM($id)
    {
        $vm = VM::with('unite')
        ->where('id_utilisateur', $id)
        ->get();
        //Array static
        $staticArray = array(
            'infos_vm' => '1',
            'data' => array(
                'vm_1'=> array(
                    'nom'=> 'VM Développement',
                    'description'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin libero purus, tempus eu venenatis eu, ullamcorper in elit. Nulla auctor nisl eu diam lacinia rutrum.',
                    'statut'=> 'on',
                    'caracteristiques'=> array(
                        'os' => 'Ubuntu 16.04 64bits',
                        'cpu' => '4',
                        'ram' => array('3200','mo'),
                        'sto_l' => array('35','GO'),
                        'sto_r' => array('2','mo'),
                    ),
                ),
                'vm_2'=> array(
                    'nom'=> 'Serveur Web Apache',
                    'description'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin libero purus, tempus eu venenatis eu, ullamcorper in elit. Nulla auctor nisl eu diam lacinia rutrum.',
                    'statut'=> 'off',
                    'caracteristiques'=> array(
                        'os' => 'Ubuntu 16.04 64bits',
                        'cpu' => '4',
                        'ram' => array('3200','mo'),
                        'sto_l' => array('35','GO'),
                        'sto_r' => array('2','mo'),
                    ),
                ),
            ),
        );

        return $vm;
    }
}
