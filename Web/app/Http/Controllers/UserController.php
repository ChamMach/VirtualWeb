<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;
use App\Classes\SocketHelper;

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
            'vm' => self::getVM('userId'),
        );
        return view('home', [
            'dataToShow' => json_encode($userData)
        ]);
    }

    /**
     * Récupère les VM de l'utilisateur
     * @return array Tableau contenant les VM
     */
    private function getVM()
    {
        //Array static
        $return = array(
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

        $socketJson = null;
        $socket = null;
        $sockethelper = new sockethelper('172.31.0.50',1333);
        //Si la socket est ouverte
        if ($sockethelper->isOnline() !== false) {
            $userID = '123';
            $dataToGet = array(
                'infos_vm' => $userID
            );
            $json = json_encode($dataToGet);
            //On envoi le JSON au socket
            $sockethelper->send_data($json);
            //On récupère le retour
            $socket = $sockethelper->read_data();
            //On ferme la socket
            $sockethelper->close_socket();
            //Decode le JSON pour avoir un array et le traiter
            $socketJson = json_decode($socket);
            foreach ($socketJson->data as $key => $value) {
                //Supprime l'ID de l'utilisateur dans le nom
                $socketJson->data->$key->nom = preg_replace('/\d*_/', '', $socketJson->data->$key->nom);
            }
        }

        return $socketJson;
        //return $staticArray;
    }
}
