<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => '/tasks'], static function (Router $router) {
    $router->get('/{id}', [TaskController::class, 'getById']);
    $router->get('/', [TaskController::class, 'getAll']);
    $router->post('/', [TaskController::class, 'create']);
    $router->patch('/{id}', [TaskController::class, 'update']);
    $router->delete('/{id}', [TaskController::class, 'delete']);
});
