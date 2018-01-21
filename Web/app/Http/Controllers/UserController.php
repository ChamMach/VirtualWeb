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
use Artisan;

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
        $vm = VM::where('id_utilisateur', $user->id)->get();
        if (sizeof($vm) == 0) {
            $vmData = array(
                'nb' => 0,
                'on' => 0,
            );
        } else {
            $vmAllume = VM::where('statut', 'on')->count();
            $vmData = array(
                'nb' => sizeof($vm),
                'on' => $vmAllume,
            );
        }
        $userData = array(
            'nom' => $nom,
            'status' => $status[$user->status],
            'verified' => Auth::check(),
            'vm' => $vm,
        );

        //Data pour le dashboard
        $userData['dashboard'] = array(
            'vm' => $vmData,
        );

        return view('home', [
            'dataToShow' => json_encode($userData)
        ]);
    }

    /**
    * Récupère les VM de l'utilisateur
    * @return array Tableau contenant les VM
    */
    public function getVM()
    {
        $user = Auth::user();
        $vm = VM::where('id_utilisateur', $user->id)->get();
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
            'erreur' => true,
            'message' => 'Un problème est survenu',
        );

        //On récupère l'id de l'utilisateur qui fait la demande
        $user = Auth::user();
        $userID = $user->id;
        //On vérifie avant si la VM n'existe pas déjà
        $vmExist = DB::table('vm')
        ->where('id_utilisateur', $userID)
        ->where('nom', $request->get('nom'))
        ->get();

        if ($request->get('systeme') == "WI7") {
            $os = 'Windows7_64';
        } elseif ($request->get('systeme') == "CE") {
            $os = 'Centos';
        }

        if (isset($os)) {
            //Si ce n'est pas le cas, on peut créer une VM
            if ($vmExist->count() == 0) {
                $socketJson = null;
                $socket = null;
                $sockethelper = new sockethelper(env('SCRIPT_VM_IP'), env('SCRIPT_VM_PORT'));
                //Si la socket est ouverte
                if ($sockethelper->isOnline() !== false) {
                    $dataToGet = array(
                        'create_vm' => $userID . "_" . $request->get('nom'),
                        'os' => $os,
                        'ram' => (integer) $request->get('ram'),
                        'cpu' => (integer) $request->get('cpu'),
                        'sto' => (integer) $request->get('stockage'),
                        'desc' => $request->get('description')
                    );
                    $json = json_encode($dataToGet);
                    //On envoi le JSON au socket
                    $sockethelper->send_data($json);
                    //On récupère le retour
                    $socket = $sockethelper->read_data();
                    //On ferme la socket
                    $sockethelper->close_socket();
                    //Decode le JSON pour avoir un array et le traiter
                    $socketJson = json_decode($socket, true);
                    //Teste du retour JSON afin de savoir si l'action a été réalisée
                    if (isset($socketJson['create_vm'])) {
                        switch ($socketJson['create_vm']) {
                            case 'true':
                            $return['erreur'] = false;
                            break;
                            case 'false_modifyfailed':
                                $return['message'] = 'Impossible de créer la VM';
                                break;
                            case 'false_cloningfailded':
                                $return['message'] = 'Impossible de créer la VM';
                                break;
                            case 'false_osunknown':
                                $return['message'] = 'OS non supporté';
                                break;
                            case 'false_vmalreadyexist':
                                $return['message'] = 'La VM existe déjà';
                                break;
                            default:
                                $return['erreur'] = false;
                                break;
                            }

                            if ($return['erreur'] == false) {
                                $return['message'] = 'VM '. $request->get('nom') .' en cours de création';
                                //Créer la VM en base
                                $vm = new VM([
                                    'id_utilisateur'     => $userID,
                                    'nom'    => $request->get('nom'),
                                    'description' => $request->get('description'),
                                    'date_creation' => date("Y-m-d H:i:s"),
                                    'statut' => 'off',
                                    'os' => $os,
                                    'cpu' => $request->get('cpu'),
                                    'ram' => $request->get('ram'),
                                    'unite_ram' => 'Mo',
                                    'sto_l' => $request->get('stockage'),
                                    'unite_sto_l' => 'Mo',
                                    'sto_r' => 0,
                                    'unite_sto_r' => 'Mo',
                                ]);
                                $saved = $vm->save();
                                if ($saved) {
                                    //On log la création
                                    DB::table('historique')->insert(
                                        ['id_user' => $userID, 'date' => date("Y-m-d H:i:s"), 'action' => 3]
                                    );
                                }
                            }
                        }
                    }

                } else {
                    $return['message'] = 'La VM existe déjà';
                }
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
                    if ($action == 'remove') {
                        //Création du JSON à envoyer au script
                        $dataToGet = array(
                            'remove_vm' => $nomVM
                        );
                        $actionFR = '"'. $vm->nom .'" supprimée';
                    } elseif ($action == 'start') {
                        $dataToGet = array(
                            'start_vm' => $nomVM
                        );
                        $actionFR = '"'. $vm->nom .'" allumée';
                    } elseif ($action == 'shutdown') {
                        $dataToGet = array(
                            'stop_vm' => $nomVM
                        );
                        $actionFR = '"'. $vm->nom .'" éteinte';
                    }

                    $json = json_encode($dataToGet);
                    //On envoi le JSON au socket
                    $sockethelper->send_data($json);
                    //On récupère le retour
                    $socket = $sockethelper->read_data();
                    //On ferme la socket
                    $sockethelper->close_socket();
                    //Decode le JSON pour avoir un array et le traiter
                    $socketJson = json_decode($socket, true);
                    //Si on est dans le cas démarrer une VM
                    if (isset($socketJson['start_vm'])) {
                        switch ($socketJson['start_vm']) {
                            case 'true':
                            $return['erreur'] = false;
                            $return['message'] = $actionFR;
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
                        switch ($socketJson['stop_vm']) {
                            case true:
                            $return['erreur'] = false;
                            $return['message'] = $actionFR;
                            //Actualise l'état de la VM en base
                            $vm->statut = 'off';
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
                    } elseif (isset($socketJson['remove_vm'])) {
                        switch ($socketJson['remove_vm']) {
                            case true:
                            $return['erreur'] = false;
                            $return['message'] = $actionFR;
                            //Supprime la VM de la base
                            $vm->delete();
                            //On met à jour l'historique
                            DB::table('historique')->insert(
                                ['id_user' => $vm->id_utilisateur, 'date' => date("Y-m-d H:i:s"), 'action' => 6]
                            );
                            break;
                            case 'false_vmdoesntexist':
                            $return['message'] = 'La VM '. $vm->nom .' n\'existe pas';
                            break;
                            case 'false_vmonline':
                            $return['message'] = 'La VM '. $vm->nom .' est en ligne';
                            break;
                            case 'false_removefailed':
                            $return['message'] = 'Impossible de supprimer la VM  '. $vm->nom;
                            break;
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

            if ($return['erreur'] == false) {
                //Permet de mettre à jour la BDD au lieu d'attendre que les cript s'exécute
                //tout seul
                Artisan::call('schedule:run');
            }

            return $return;
        }

        public function editVM(Request $request)
        {
            $return = array(
                'erreur' => true,
                'message' => 'Un problème est survenu',
            );

            //On récupère l'id de l'utilisateur qui fait la demande
            $user = Auth::user();
            $userID = $user->id;

            //On vérifie avant si la VM n'existe pas déjà
            $vmExist = $this->vmExist($request->get('nom'), $userID);

            //Si ce n'est pas le cas, on peut créer une VM
            if (!is_null($vmExist)) {
                $socketJson = null;
                $socket = null;
                $sockethelper = new sockethelper(env('SCRIPT_VM_IP'), env('SCRIPT_VM_PORT'));
                //Si la socket est ouverte
                if ($sockethelper->isOnline() !== false) {
                    $dataToGet = array(
                        'modify_vm' => $userID . "_" . $request->get('nom'),
                        'ram' => (integer) $request->get('ram'),
                        'cpu' => (integer) $request->get('cpu'),
                        'sto' => (integer) $request->get('stockage'),
                        'desc' => $request->get('description')
                    );
                    $json = json_encode($dataToGet);
                    //On envoi le JSON au socket
                    $sockethelper->send_data($json);
                    //On récupère le retour
                    $socket = $sockethelper->read_data();
                    //On ferme la socket
                    $sockethelper->close_socket();
                    //Decode le JSON pour avoir un array et le traiter
                    $socketJson = json_decode($socket, true);
                    //Teste du retour JSON afin de savoir si l'action a été réalisée
                    if (isset($socketJson['modify_vm'])) {
                        switch ($socketJson['modify_vm']) {
                            case 'true':
                            $return['erreur'] = false;
                            break;
                            case 'false_vmdoesntexist':
                            $return['message'] = 'La VM n\'existe pas';
                            break;
                            case 'false_vmonline':
                            $return['message'] = 'La VM est en ligne';
                            break;
                            case 'false_vmstateunknown':
                            $return['message'] = 'Statut VM incconu';
                            break;
                        }

                        if ($return['erreur'] == false) {
                            $return['message'] = 'VM '. $request->get('nom') .' en cours de modification';
                            //Met à jour la VM en base
                            $vmExist->description = $request->get('description');
                            $vmExist->cpu = $request->get('cpu');
                            $vmExist->ram = $request->get('ram');
                            $vmExist->sto_l = $request->get('stockage');

                            //On log la modification
                            DB::table('historique')->insert(
                                ['id_user' => $userID, 'date' => date("Y-m-d H:i:s"), 'action' => 7]
                            );
                        }
                    }
                }

            } else {
                $return['message'] = 'La VM n\'existe pas';
            }

            return $return;
        }

        private function vmExist($vm, $userID)
        {
            $exist = false;
            $vmExist = DB::table('vm')
            ->where('id_utilisateur', $userID)
            ->where('nom', $vm)
            ->get();

            return $vmExist;
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
