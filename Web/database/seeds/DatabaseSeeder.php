<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('historique')->delete();
        DB::table('actions')->delete();
        DB::table('users')->delete();
        DB::table('users')->insert([
            'prenom'     => 'Baptiste',
            'nom' => 'Bisson',
            'email'    => 'bbisson.baptiste@gmail.com',
            'password' => Hash::make('baptiste'),
            'status' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('users')->insert([
            'prenom'     => 'Chamseddine',
            'nom' => 'Saadoune',
            'email'    => 'cham@gmail.com',
            'password' => Hash::make('chamseddine'),
            'status' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('users')->insert([
            'prenom'     => 'Jean',
            'nom' => 'Eude',
            'email'    => 'jeaneude@gmail.com',
            'password' => Hash::make('eude'),
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        // DB::table('unite')->insert([
        //     'id' => 1,
        //     'type' => 'mo'
        // ]);
        // DB::table('unite')->insert([
        //     'id' => 2,
        //     'type' => 'go'
        // ]);
        // DB::table('vm')->insert([
        //     'id_utilisateur'     => 1,
        //     'date_creation' => date("Y-m-d H:i:s"),
        //     'nom'    => 'VM Développement',
        //     'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin libero purus, tempus eu venenatis eu, ullamcorper in elit. Nulla auctor nisl eu diam lacinia rutrum.',
        //     'statut' => 'on',
        //     'os' => 'Ubuntu 16.04',
        //     'cpu' => 4,
        //     'ram' => 3000,
        //     'id_unite_ram' => 1,
        //     'sto_l' => 20,
        //     'id_unite_sto_l' => 1,
        //     'sto_r' => 2,
        //     'id_unite_sto_r' => 2,
        // ]);
        DB::table('actions')->insert([
            'description' => 'Connexion'
        ]);
        DB::table('actions')->insert([
            'description' => 'Déconnexion'
        ]);
        DB::table('actions')->insert([
            'description' => 'Création VM'
        ]);
        DB::table('actions')->insert([
            'description' => 'Démarrage VM'
        ]);
        DB::table('actions')->insert([
            'description' => 'Extinction VM'
        ]);
        DB::table('actions')->insert([
            'description' => 'Suppression VM'
        ]);
        DB::table('actions')->insert([
            'description' => 'Mise à jour VM'
        ]);
    }
}
