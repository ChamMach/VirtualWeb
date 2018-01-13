<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Classes\SocketHelper;
use App\VM;
use Log;
use App\User;

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
            $time = microtime(true);
            $socketJson = $socket = null;
            $sockethelper = new sockethelper(env('SCRIPT_VM_IP'), env('SCRIPT_VM_PORT'));
            //Si la socket est ouverte
            if ($sockethelper->isOnline() !== false) {
                $dataToGet = array(
                    'infos_vm' => 'all'
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

                //Pour chaque VM en BDD
                $vm_base = DB::table('vm')->select('id_vm', 'id_utilisateur', 'nom')->get();
                if (sizeof($vm_base) !== 0) {
                    foreach ($socketJson->data as $key => $value) {
                        if (strpos($value->nom, '_') !== false) {
                            $listeNomsVM[] = $value->nom;
                        }
                    }
                    foreach ($vm_base as $key => $value) {
                        //Si la vm n'existe pas dans le json récupérer avec le script
                        if (!in_array($value->id_utilisateur . '_' . $value->nom, $listeNomsVM)) {
                            //On supprime la VM de la base
                            DB::table('vm')->where('id_vm', $value->id_vm)->delete();
                            Log::info('Script import : La vm '. $value->id_utilisateur . '_' . $value->nom, $listeNomsVM .' n\'existe pas en base, suppression effectuée');
                        }
                    }
                }

                //Pour chaque VM
                foreach ($socketJson->data as $key => $value) {
                    if (strpos($value->nom, '_') !== false) {
                        //On coupe une chaîne en segment grâce au délimiteur _
                        $explodeUser = explode("_", $value->nom);
                        $idUser = $explodeUser[0];
                        $nomVm = $explodeUser[1];

                        //On regarde si l'utilisateur existe en base
                        $user_exist = User::where('id', $idUser)
                        ->first();
                        if (!is_null($user_exist)) {
                            //On recherche si la VM existe déjà
                            $vm_exist = VM::where('id_utilisateur', $idUser)
                            ->where('nom', $explodeUser[1])
                            ->first();

                            //Si la VM n'existe pas, on la crée
                            if (is_null($vm_exist)) {
                                $vm = VM::create(array(
                                    'id_utilisateur'     => $idUser,
                                    'nom'    => $nomVm,
                                    'description' => $value->description,
                                    'statut' => $value->statut,
                                    'os' => $value->caracteristiques->os,
                                    'cpu' => $value->caracteristiques->cpu,
                                    'ram' => $value->caracteristiques->ram["0"],
                                    'unite_ram' => $value->caracteristiques->ram["1"],
                                    'sto_l' => $value->caracteristiques->sto_l["0"],
                                    'unite_sto_l' => $value->caracteristiques->sto_l["1"],
                                    'sto_r' => $value->caracteristiques->sto_r["0"],
                                    'unite_sto_r' => $value->caracteristiques->sto_r["1"],
                                ));
                                //Insertion des données
                                $vm->save();
                                Log::info('Script import : Nouvelle VM '. $nomVm);
                            } else {
                                //Sinon on la met à jour
                                $vm_exist->description = $value->description;
                                $vm_exist->statut = $value->statut;
                                $vm_exist->os = $value->caracteristiques->os;
                                $vm_exist->cpu = $value->caracteristiques->cpu;
                                $vm_exist->ram = $value->caracteristiques->ram["0"];
                                $vm_exist->unite_ram = $value->caracteristiques->ram["1"];
                                $vm_exist->sto_l = $value->caracteristiques->sto_l["0"];
                                $vm_exist->unite_sto_l = $value->caracteristiques->sto_l["1"];
                                $vm_exist->sto_r = $value->caracteristiques->sto_r["0"];
                                $vm_exist->unite_sto_r = $value->caracteristiques->sto_r["1"];
                                //Mise à jour
                                $vm_exist->save();
                                Log::info('Script import : Mise à jour VM '. $nomVm);
                            }
                        } else {
                            Log::info('Script import : L\'utilisateur '. $idUser .' n\'existe pas en base, impossible d\'importer cette ligne');
                        }
                    }

                }
            }
            Log::info('Script import : Délais de ' . (microtime(true) - $time) . 's');
            Log::info('Fin import script');
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
