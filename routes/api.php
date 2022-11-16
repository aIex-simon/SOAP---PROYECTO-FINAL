<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

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

Route::get('/', function () {
    return new JsonResponse([
        'app' => 'Api BM ' . config('bm.api_version'),
        'env' => App::environment(),
    ]);
})->name('api.get.home');

Route::get('/unauthenticated', function () {
    return new JsonResponse([
        'apiVersion' => config('bm.api_version'),
        'message' => 'unauthenticated'
    ], Response::HTTP_UNAUTHORIZED);
})->name('api.get.unauthenticated');
