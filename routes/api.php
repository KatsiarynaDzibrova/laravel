<?php

use App\Http\Controllers\RecordingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('reset', 'AuthController@reset');
    Route::view('reset', 'reset');

});

