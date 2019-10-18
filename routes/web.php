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

use App\Http\Controllers\Auth\Google2FAController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/*
 * Google 2FA routes
 */
Route::get('/2fa/activate', [Google2FAController::class, 'activate2FA'])->name('2fa.activate');
Route::post('/2fa/activate', [Google2FAController::class, 'assign2FA']);
Route::get('/2fa/deactivate', [Google2FAController::class, 'deactivate2FA'])->name('2fa.deactivate');

Route::get('/home', 'HomeController@index')->name('home');
