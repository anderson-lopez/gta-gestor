<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\employeeController;

Route::get('/employees', [employeeController::class, 'index']);

Route::get('employees/{id}', [employeeController::class, 'show']);

Route::post('/employees', [employeeController::class, 'store']);

Route::put('employees/{id}',[employeeController::class, 'update']);

Route::delete('employees/{id}', [employeeController::class, 'destroy']);
