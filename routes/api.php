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

Route::prefix("packages")->controller(PackageController::class)->group(function(){
    Route::get("/","getPackage")->name("getAllPackage");
    Route::get("/{id}","getByTransId")->name("getPackageByID");
    Route::post("/","store")->name("storePackage");
    Route::put("/{id}","replace")->name("replacePackage");
    Route::patch("/{id}","update")->name("patchPackage");
    Route::delete("/{id}","delete")->name("deletePackage");
});
