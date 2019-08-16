<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use DB;


class LogoutController
{
    public function deconnexion()
    {
        $user = Auth::user();
        // DB::table('historique')->insert(
        //     ['id_user' => $user->id, 'date' => date("Y-m-d H:i:s"), 'action' => 2]
        // );
        var_dump($user);
        // Auth::logout();
        // Session::flush();
        // return Redirect::to('/');
    }
}
