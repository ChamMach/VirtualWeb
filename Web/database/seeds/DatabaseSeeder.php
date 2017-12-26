<?php

use Illuminate\Database\Seeder;

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
        User::create(array(
            'name'     => 'Jean Baptiste',
            'username' => 'baptiste',
            'email'    => 'bbisson.baptiste@gmail.com',
            'password' => Hash::make('awesome'),
        ));
    }
}
