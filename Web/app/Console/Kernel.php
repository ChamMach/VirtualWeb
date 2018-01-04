<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Classes\SocketHelper;
use App\VM;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //\App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
            $socketJson = null;
            $socket = null;
            $sockethelper = new sockethelper('localhost',1333);
            //Si la socket est ouverte
            if ($sockethelper->isOnline() !== false) {
                $userID = '1';
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
                //Pour chaque VM
                foreach ($socketJson->data as $key => $value) {
                    //Supprime l'ID de l'utilisateur dans le nom
                    $socketJson->data->$key->nom = preg_replace('/\d*_/', '', $socketJson->data->$key->nom);

                    //On fait la correspondance string vers int
                    if ($value->caracteristiques->ram["1"] == 'Mo') {
                        $id_unite_ram = 1;
                    } else {
                        $id_unite_ram = 2;
                    }
                    if ($value->caracteristiques->sto_l["1"] == 'Mo') {
                        $id_unite_sto_l = 1;
                    } else {
                        $id_unite_sto_l = 2;
                    }
                    if ($value->caracteristiques->sto_r["1"] == 'Mo') {
                        $id_unite_sto_r = 1;
                    } else {
                        $id_unite_sto_r = 2;
                    }

                    $vm_exist = VM::where('id_utilisateur',1)
                        ->where('nom',$value->nom)
                        ->first();

                    //Si la VM n'existe pas, on la crée
                    if (is_null($vm_exist)) {
                        $vm = VM::create(array(
                            'id_utilisateur'     => 1,
                            'nom'    => $value->nom,
                            'description' => $value->description,
                            'statut' => $value->statut,
                            'os' => $value->caracteristiques->os,
                            'cpu' => $value->caracteristiques->cpu,
                            'ram' => $value->caracteristiques->ram["0"],
                            'id_unite_ram' => $id_unite_ram,
                            'sto_l' => $value->caracteristiques->sto_l["0"],
                            'id_unite_sto_l' => $id_unite_sto_l,
                            'sto_r' => $value->caracteristiques->sto_r["0"],
                            'id_unite_sto_r' => $id_unite_sto_r,
                        ));
                        //Insertion des données
                        $vm->save();
                    } else {
                        //On la met à jour
                        $vm_exist->nom = $value->nom;
                        $vm_exist->description = $value->description;
                        $vm_exist->statut = $value->statut;
                        $vm_exist->os = $value->caracteristiques->os;
                        $vm_exist->cpu = $value->caracteristiques->cpu;
                        $vm_exist->ram = $value->caracteristiques->ram["0"];
                        $vm_exist->id_unite_ram = $id_unite_ram;
                        $vm_exist->sto_l = $value->caracteristiques->sto_l["0"];
                        $vm_exist->id_unite_sto_l = $id_unite_sto_l;
                        $vm_exist->sto_r = $value->caracteristiques->sto_r["0"];
                        $vm_exist->id_unite_sto_r = $id_unite_sto_r;
                        //Mise à jour
                        $vm_exist->save();
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
