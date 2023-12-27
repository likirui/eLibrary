<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLoanController;


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

Route::get('/users', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'check']);
Route::post('/refresh', [RegisterController::class, 'refresh']);
Route::get('/userdetail/{id}', [RegisterController::class, 'show']);
Route::post('/user/{id}', [LoginController::class, 'logout']);
Route::put('/edituser/{id}', [RegisterController::class, 'update']);
Route::post('/refresh', [RegisterController::class, 'refresh']);

//BOOKS
Route::post('/addbook', [BookController::class, 'store']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/avbooks', [BookController::class, 'avbooks']);
Route::get('/book/{id}', [BookController::class, 'show']);
Route::post('/book/{id}', [BookController::class, 'update']);
Route::post('/requestbook/{id}', [BookLoanController::class, 'requestbook']);
Route::get('/bookloans', [BookLoanController::class, 'bookloans']);
Route::post('/approvebook/{id}', [BookLoanController::class, 'approvebook']);
Route::post('/extendbook/{id}', [BookLoanController::class, 'extendbook']);
Route::post('/returnbook/{id}', [BookLoanController::class, 'returnbook']);
Route::get('/approvedbooks', [BookLoanController::class, 'approvedbooks']);
Route::get('/myloans/{id}', [BookLoanController::class, 'myloans']);
Route::get('/gettotals', [BookLoanController::class, 'gettotals']);
Route::get('/totalborrowedbooks', [BookLoanController::class, 'totalborrowedbooks']);
Route::get('/totalregisteredusers', [BookLoanController::class, 'totalregisteredusers']);

Route::get('/userborrowedbooks/{id}', [BookLoanController::class, 'userborrowedbooks']);
Route::get('/usertotalpenalty/{id}', [BookLoanController::class, 'usertotalpenalty']);
Route::get('/usertotals/{id}', [BookLoanController::class, 'usertotals']);

