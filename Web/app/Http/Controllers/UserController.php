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
use Log;

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
        $vm = self::getVM($user->id);
        $userData = array(
            'nom' => $nom,
            'status' => $status[$user->status],
            'verified' => Auth::check(),
            'vm' => $vm,
        );

        //Data pour le dashboard
        $userData['dashboard'] = array(
            'vm' => sizeof($vm),
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
     * Exécute les actions sur les VM (supprimer, allumer, éteindre)
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
        $nomVM = $request->get('nomVM');
        
        //Récupère le nom de la VM
        $vm = VM::where('nom', explode("_", $nomVM)[1])
        ->first();
        
        //Si la VM existe bien
        if (!is_null($vm)) {
            $socketJson = null;
            $socket = null;
            $sockethelper = new sockethelper(env('SCRIPT_VM_IP'), env('SCRIPT_VM_PORT'));
            //Si la socket est ouverte
            if ($sockethelper->isOnline() !== false) {
                if ($action == 'delete') {
                    //Création du JSON à envoyer au script
                    $dataToGet = array(
                        'delete_vm' => $nomVM
                    );
                    $actionFR = 'supprimer la VM : ' . $vm->nom;
                } elseif ($action == 'start') {
                    $dataToGet = array(
                        'start_vm' => $nomVM
                    );
                    $actionFR = 'démarrer la VM : ' . $vm->nom;
                } elseif ($action == 'shutdown') {
                    $dataToGet = array(
                        'stop_vm' => $nomVM
                    );
                    $actionFR = 'éteindre la VM : ' . $vm->nom;
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
                //Teste du retour JSON afin de savoir si l'action a été réalisée
                if (isset($socketJson['start_vm']) or isset($socketJson['stop_vm'])) {
                    //Si on est dans le cas démarrer une VM
                    if (isset($socketJson['start_vm'])) {
                        switch ($socketJson['start_vm']) {
                            case true:
                                $return['erreur'] = false;
                                $return['message'] = 'L\'action '. $actionFR . ' a été réalisée';
                                //Actualise l'état de la VM en base
                                $vm->statut = 'on';
                                $vm->save();
                                //On met à jour l'historique
                                DB::table('historique')->insert(
                                    ['id_user' => $vm->id_utilisateur, 'date' => date("Y-m-d H:i:s"), 'action' => 4]
                                );
                                break;
                            case 'false_vmalreadyonline':
                                $return['message'] = 'La VM '. $vm->nom .' est déjà allumée';
                                break;
                            case 'false_sessionlocked':
                                $return['message'] = 'La VM '. $vm->nom .' dispose d\'une session inutilisable';
                                break;
                            case 'false_sessionunknown':
                                $return['message'] = 'L\'état de la VM '. $vm->nom .' est inconnu';
                                break;
                            case 'false_startfailed':
                                $return['message'] = 'Impossible de démarrer la VM ' . $vm->nom;
                                break;
                            case 'false_statevmunknown':
                                $return['message'] = 'Etat inconnu de la VM '. $vm->nom .', impossible de la démarrer';
                                break;
                        }
                    } elseif (isset($socketJson['stop_vm'])) {
                        switch ($socketJson['start_vm']) {
                            case true:
                                $return['erreur'] = false;
                                $return['message'] = 'L\'action '. $actionFR . ' a été réalisée';
                                //Actualise l'état de la VM en base
                                $vm->statut = 'on';
                                $vm->save();
                                //On met à jour l'historique
                                DB::table('historique')->insert(
                                    ['id_user' => $vm->id_utilisateur, 'date' => date("Y-m-d H:i:s"), 'action' => 5]
                                );
                                break;
                            case 'false_vmalreadyoff':
                                $return['message'] = 'La VM '. $vm->nom .' est déjà éteinte';
                                break;
                            case 'false_sessionlocked':
                                $return['message'] = 'La VM '. $vm->nom .' dispose d\'une session inutilisable';
                                break;
                            case 'false_sessionunknown':
                                $return['message'] = 'L\'état de la VM '. $vm->nom .' est inconnu';
                                break;
                            case 'false_stopfailed':
                                $return['message'] = 'Impossible d\'éteindre la VM ' . $vm->nom;
                                break;
                            case 'false_statevmunknown':
                                $return['message'] = 'Etat inconnu de la VM '. $vm->nom .', impossible de l\'éteindre';
                                break;
                        }
                    }
                } else {
                    //Il est possible que le socket ne renvoie rien. Actuellement on attend 2s
                    //Si aucun retour, on arrive dans ce cas
                    $return['message'] = 'Problème de connexion, merci de réessayer plus tard';
                }
            } else {
                $return['message'] = 'Problème de connexion, merci de réessayer plus tard';
            }
        } else {
            $return['message'] = 'La VM n\'existe pas';
        }

        //On ajoute au fichier log ce qu'il c'est passé ici pour garder un historique
        Log::debug('Action VM '. $nomVM .' : ' . json_encode($return));

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
