<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;
use App\Classes\SocketHelper;
use App\VM;
use Session;
use Illuminate\Support\Facades\Redirect;

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
        $vm = VM::where('id_utilisateur', $id)
        ->get();
        //Si aucune VM pour l'utilisateur on remplace l'array par null
        if (sizeof($vm) == 0) {
            $vm = null;
        }
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
    
    /**
     * Création d'une VM
     * @param  Request $request Données du formulaire
     * @return Array            Erreur + message ou succes
     */
    public function createVM(Request $request)
    {
        $return = array(
            'erreur' => true
        );
        
        //On récupère l'id de l'utilisateur qui fait la demande
        $user = Auth::user();
        $userID = $user->id;
        //On vérifie avant si la VM n'existe pas déjà
        $vmExist = DB::table('vm')
        ->where('nom', $userID. '_' .$request->get('nom'))
        ->get();
        //Si ce n'est pas le cas, on peut créer un utilisateur
        if ($vmExist->count() == 0) {
            $vm = new VM([
                'id_utilisateur'     => $userID,
                'nom'    => $request->get('nom'),
                'description' => $request->get('description'),
                'date_creation' => date("Y-m-d H:i:s"),
                'statut' => $request->get('statut'),
                'os' => $request->get('os'),
                'cpu' => $request->get('cpu'),
                'ram' => $request->get('ram'),
                'unite_ram' => 'Mo',
                'sto_l' => $request->get('sto_l'),
                'unite_sto_l' => 'Mo',
                'sto_r' => $request->get('sto_r'),
                'unite_sto_r' => 'Mo',
            ]);
            //On le sauvegarde en base
            $saved = $user->save();
            if ($saved) {
                //On log la création
                DB::table('historique')->insert(
                    ['id_user' => $userID, 'date' => date("Y-m-d H:i:s"), 'action' => 3]
                );
                $return['erreur'] = false;
                $return['message'] = 'VM crée';
            }
        } else {
            $return['message'] = 'La VM existe déjà';
        }
        return $return;
    }
    
    /**
     * Exécute les actions sur les VM (supprimer, allumer, ééteindre)
     * @param  Request $request Données du formulaire
     * @return Array           Erreur + message ou succes
     */
    public function sendAction(Request $request)
    {
        $return = array(
            'erreur' => true
        );
        //Récupère l'action à réaliser
        $action = $request->get('action');
        $vmID = $request->get('id');
        
        $socketJson = null;
        $socket = null;
        $sockethelper = new sockethelper('localhost',1333);
        //Si la socket est ouverte
        if ($sockethelper->isOnline() !== false) {
            if ($action == 'delete') {
                $dataToGet = array(
                    'delete_vm' => $vmID
                );
                $actionFR = 'supprimer la VM : ' . $request->get('id');
            } elseif ($action == 'start') {
                $dataToGet = array(
                    'start_vm' => $vmID
                );
                $actionFR = 'démarrer la VM : ' . $request->get('id');
            } elseif ($action == 'shutdown') {
                $dataToGet = array(
                    'shutdown_vm' => $vmID
                );
                $actionFR = 'éteindre la VM : ' . $request->get('id');
            }
            $json = json_encode($dataToGet);
            //On envoi le JSON au socket
            $sockethelper->send_data($json);
            //On récupère le retour
            $socket = $sockethelper->read_data();
            //On ferme la socket
            $sockethelper->close_socket();
            //Decode le JSON pour avoir un array et le traiter
            $socketJson = json_decode($socket);
            //Tester ici le retour du JSON afin de savoir si l'action a été réalisée
            
            $return['erreur'] = false;
            $return['message'] = 'L\'action '. $actionFR . ' a été réalisée';
        } else {
            $return['message'] = 'Problème de connexion, merci de réessayer plus tard';
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
