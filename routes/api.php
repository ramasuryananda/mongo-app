<?php

use App\Http\Controllers\PackageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("package")->controller(PackageController::class)->group(function(){
    Route::get("/","getPackage");
    Route::get("/{id}","getByTransId");
    Route::post("/","store");
    Route::put("/{id}","replace");
    Route::patch("/{id}","update");
    Route::delete("/{id}","delete");
});
