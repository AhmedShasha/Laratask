<?php

use App\Http\Controllers\New\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('welcome');
});

Route::get('/welcome', function () {
    return view('Laratask.welcome');
})->middleware(['auth'])->name('welcome');

Route::group(['middleware' => 'auth', 'namespace' => 'App\Http\Controllers\Laratask'], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/storeFile', 'HomeController@store')->name('storeFile');
    Route::get('/perviewFile/{id}', 'HomeController@preview')->name('perviewFile');
    Route::delete('/deletefile/{id}', 'HomeController@destroy')->name('deletefile');

    Route::get('/gerFiles/{id}' , 'HomeController@myFiles')->name('getFiles');
    Route::get('/userFiles/{id}' , 'HomeController@userFiles')->name('userFiles');

    // users routes
    Route::get('/allUsers','UserController@index')->name('allUsers');
    Route::get('/createUser','UserController@create')->name('createUser');
    Route::post('/storeUser','UserController@store')->name('storeUser');
    Route::get('/editUser/{id}','UserController@edit')->name('editUser');
    Route::post('/updateUser/{id}','UserController@update')->name('updateUser');
    Route::delete('/deleteUser/{id}','UserController@destroy')->name('deleteUser');

});


require __DIR__.'/auth.php';
