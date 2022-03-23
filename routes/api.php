<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserInfoPendingRequestController;

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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/fetch_all_pending_requests', [UserInfoPendingRequestController::class, 'fetch_all_pending_requests']);
    Route::post('/create_new_user_info', [UserInfoPendingRequestController::class, 'create_new_user_info']);
    Route::post('/update_user_info', [UserInfoPendingRequestController::class, 'update_user_info']);
    Route::get('/delete_user_info/{id}/{request_type}', [UserInfoPendingRequestController::class, 'delete_user_info']);

    Route::put('/approve_request/{id}/{request_type}', [UserInfoPendingRequestController::class, 'approve_request']);
    Route::delete('/decline_request/{id}', [UserInfoPendingRequestController::class, 'decline_request']);
});
