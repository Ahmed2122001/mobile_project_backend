<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\auth\AuthController;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Jwt;
use App\Http\Controllers\API\BusinessServiceContoller;
use App\Http\Controllers\API\FavoriteServicesController;
use App\Http\Controllers\API\CompanyProfile;

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

Route::prefix('/auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/EditProfile/{id}', [AuthController::class, 'EditProfile'])->middleware('auth:api');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});
Route::prefix('/businessService')->group(function () {
    Route::post('/storeService/{user_id}', [BusinessServiceContoller::class, 'storeService'])->middleware('auth:api');
    Route::get('/showService/{id}', [BusinessServiceContoller::class, 'showService'])->middleware('auth:api');
    Route::get('/showAllServices', [BusinessServiceContoller::class, 'showAllServices'])->middleware('auth:api');
});
Route::prefix('/favoriteServices')->group(function () {
    Route::get('/showAllfavoriteServices/{user_id}', [FavoriteServicesController::class, 'showAllfavoriteServices'])->middleware('auth:api');
    Route::post('/addFavorite', [FavoriteServicesController::class, 'addFavorite'])->middleware('auth:api');
    Route::post('/removeFavorite/{id}', [FavoriteServicesController::class, 'removeFavorite'])->middleware('auth:api');
    Route::get('/is_exist/{user_id}/{business_service_id}', [FavoriteServicesController::class, 'is_exist'])->middleware('auth:api');
});
Route::prefix('/companyProfile')->group(function () {
    Route::get('/getcompanyProfile/{servise_id}', [CompanyProfile::class, 'getcompanyProfile'])->middleware('auth:api');
    Route::get('/getAllBusinessServicesForSpicficCompany/{user_id}', [CompanyProfile::class, 'getAllBusinessServicesForSpicficCompany'])->middleware('auth:api');
    Route::get('/getAllCompanies', [CompanyProfile::class, 'getAllCompanies'])->middleware('auth:api');
    Route::post('/getDistance', [CompanyProfile::class, 'getDistance'])->middleware('auth:api');
    Route::post('/getAllcompaniesforSpicficService', [CompanyProfile::class, 'getAllCompaniesForSpecificService'])->middleware('auth:api');
});
