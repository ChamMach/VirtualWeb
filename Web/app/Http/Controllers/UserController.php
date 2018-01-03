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
        $this->middleware('isUser');
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
            'vm' => self::getVM('userId'),
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

    private function getVM($userID)
    {
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
                        'ram' => array(
                            'nb' => '3200',
                            'unite' => 'mo',
                        ),
                        'sto_l' => array(
                            'nb' => '35',
                            'unite' => 'GO',
                        ),
                        'sto_r' => array(
                            'nb' => '2',
                            'unite' => 'mo',
                        ),
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
                        'sto_r' => array(
                            'nb' => '2',
                            'unite' => 'mo',
                        ),
                    ),
                ),
            ),
        );

        $socket = null;
        $sockethelper = new sockethelper('172.31.0.50',1333);
        $userID = '123';
        if (1 == 1) {
            $dataToGet = array(
                'infos_vm' => $userID
            );
            $string = json_encode($dataToGet);
            $sockethelper->send_data($string);
            $socket = $sockethelper->read_data();
            $sockethelper->close_socket();
        } else {

        }
        $data = json_decode($socket);
        foreach ($data->data as $key => $value) {
           $data->data->$key->nom = preg_replace('/\d*_/', '', $data->data->$key->nom);
        }

        return $data;
        //return $return;s
    }
}
