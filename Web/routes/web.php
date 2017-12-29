<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::middleware(['auth'])->group(function ()
{
    Route::get('/accueil', 'UserController@index')->name('accueil');
    Route::get('/ajouter', 'UserController@index')->name('accueil');
    Route::get('/supprimer', 'UserController@index')->name('accueil');
    Route::get('/vm', 'UserController@index')->name('accueil');
    Route::get('/conteneur', 'UserController@index')->name('accueil');
    Route::get('/utilisateurs', 'UserController@index')->name('accueil');
    
    Route::post('get_users', array('uses' => 'UserController@getUsers'));
    Route::post('create_user', array('uses' => 'UserController@createUser'));
});

Route::get('/connexion', array('uses' => 'Auth\LoginController@loginPage'))->name('connexion');
Route::post('connexion', array('uses' => 'Auth\LoginController@connexion'));
Route::get('/deconnexion', function()
{
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
});
