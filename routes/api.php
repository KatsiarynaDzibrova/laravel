<?php

use App\Http\Controllers\RecordingsController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/recordings', [RecordingsController::class, 'getAllRecordings']);


Route::get('/recordings/artist/{name}', [RecordingsController::class, 'getRecordingByArtist']);

Route::post('/recordings', [RecordingsController::class, 'createRecording']);

Route::put('/recordings/{recording}', [RecordingsController::class, 'updateRecording']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [ 'as' => 'login', 'uses' => 'AuthController@login']);
    Route::view('login', 'login');
    Route::post('register',  'AuthController@register');
    Route::view('register', 'register');
    Route::post('reset', 'AuthController@reset')->middleware('guest')->name('password.email');
    Route::get('reset', function () {
        return view('reset');
    })->middleware('guest')->name('password.request');

    Route::get('/reset-password/{token}', function ($token) {
        return view('reset-password', ['token' => $token]);
    })->middleware('guest')->name('password.reset');

    Route::post('/reset-password', 'AuthController@reset_password'
    )->middleware('guest')->name('password.update');

});
