<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeesController;

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
Route::post('login', [LoginController::class, 'login']);



Route::group(['middleware' => ['jwt.verify']], function() {

    Route::get("employees", [EmployeesController::class, "employeesList"]);
    Route::post("store", [EmployeesController::class, "store"]);
    Route::post("update", [EmployeesController::class, "update"]);
    Route::post("delete", [EmployeesController::class, "delete"]);

    Route::post("addAddress", [EmployeesController::class, "addAddress"]);

});
