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
        DB::table('action')->delete();
        DB::table('users')->delete();
        DB::table('users')->insert([
            'prenom'     => 'Baptiste',
            'nom' => 'Bisson',
            'email'    => 'bbisson.baptiste@gmail.com',
            'password' => Hash::make('baptiste'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('users')->insert([
            'prenom'     => 'Chamseddine',
            'nom' => 'Saadoune',
            'email'    => 'cham@gmail.com',
            'password' => Hash::make('chamseddine'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('historique')->insert([
            'id_historique' => '1',
            'date' => date("Y-m-d H:i:s"),
            'action'    => '1',
        ]);
        DB::table('action')->insert([
            'description' => 'Connexion'
        ]);
        DB::table('action')->insert([
            'description' => 'Déconnexion'
        ]);
    }
}
