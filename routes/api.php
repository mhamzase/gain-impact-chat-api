<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//API route for signUp new user
Route::post('/signup', [App\Http\Controllers\API\UserController::class, 'signUp']);
//API route for login user
Route::post('/login', [App\Http\Controllers\API\UserController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return response()->json([
            'status_code' => 200,
            'data' => auth()->user(),
        ], 200);
    });

    //get all users 
    Route::get('/users', [App\Http\Controllers\API\UserController::class, 'users']);

    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\UserController::class, 'logout']);
});