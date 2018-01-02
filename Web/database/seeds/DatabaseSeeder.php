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
        DB::table('action')->insert([
            'description' => 'Connexion'
        ]);
        DB::table('action')->insert([
            'description' => 'Déconnexion'
        ]);
    }
}
