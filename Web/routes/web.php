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

Route::group(['middleware' => ['isUser']], function ()
{
    Route::get('/accueil', 'UserController@index')->name('accueil');
    Route::get('/ajouter', 'UserController@index')->name('ajouter');
    Route::get('/supprimer', 'UserController@index')->name('supprimer');
    Route::get('/vm', 'UserController@index')->name('vm');
    Route::get('/conteneur', 'UserController@index')->name('contenuer');
});

//Middleware déclaré dans Http/Kernel.php
Route::group(['middleware' => ['isAdmin']], function ()
{
    Route::get('/administration', 'Admin\AdminController@index')->name('administration');
    Route::get('/utilisateurs', 'Admin\AdminController@index')->name('utilisateurs');
    //Route::post('get_users', array('uses' => 'UserController@getUsers'));
    Route::post('create_user', array('uses' => 'Admin\AdminController@createUser'));
    Route::post('delete_user', array('uses' => 'Admin\AdminController@deleteUser'));
});

Route::get('/connexion', array('uses' => 'Auth\LoginController@loginPage'))->name('connexion');
Route::post('connexion', array('uses' => 'Auth\LoginController@connexion'));
Route::get('/deconnexion', function()
{
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
})->name('deconnexion');
