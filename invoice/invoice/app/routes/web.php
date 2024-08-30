<?php

use App\Http\Controllers\Client;
use App\Http\Controllers\Invoice;
use App\Http\Controllers\Products;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect(route('app.dashboard'));
    }
    return view('auth.login');
})->name('login');
Route::post('/', [User::class, 'login'])->name('login.attempt');

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect(route('app.dashboard'));
    }
    return view('auth.register');
})->name('register');

Route::post('/register', [User::class, 'register'])->name('register.attempt');

/* Update end-user details. Currently handling user verification */
Route::get('/user-action/{u}', [User::class, 'updateUser'])->name('user.update');

/* Forgot password section for end-users */
Route::match(['get', 'post'], '/forgot-password/{u?}', [User::class, 'forgotPassword'])->name('auth.password');

/* AUTHENTICATED ROUTES */
Route::middleware(['auth', 'profileCompletion'])->group(function () {
    Route::get('/logout', [User::class, 'logout'])->name('auth.logout');
    Route::get('/dashboard', [User::class, 'dashboard'])->name('app.dashboard');
        Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'list'])->name('users.list');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/create', [UserController::class, 'store'])->name('users.store');
        // Route for editing user with UUID parameter
        Route::get('/edit/{uuid}', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/edit/{id}', [UserController::class, 'update'])->name('users.update');
    });
    
    


    Route::group(['prefix' => 'clients'], function () {
        Route::get('/', [Client::class, 'list'])->name('app.client.list');
        Route::match(['get', 'post'], '/create', [Client::class, 'create'])->name('app.client.add');
        Route::match(['get', 'post'], '/edit/{uuid}', [Client::class, 'edit'])->name('app.client.edit');
        Route::get('/delete/{uuid}', [Client::class, 'delete'])->name('app.client.delete');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [Products::class, 'list'])->name('app.product.list');
        Route::match(['get', 'post'], '/create', [Products::class, 'create'])->name('app.product.add');
        Route::match(['get', 'post'], '/edit/{uuid}', [Products::class, 'edit'])->name('app.product.edit');
        Route::get('/delete/{uuid}', [Products::class, 'delete'])->name('app.product.delete');
    });

    Route::group(['prefix' => 'invoices'], function () {
        Route::get('/', [Invoice::class, 'list'])->name('app.invoice.list');
        Route::get('/particulars/{invoice_id}', [Invoice::class, 'getParticulars'])->name('app.invoice.particulars');
        Route::match(['get', 'post'], '/create', [Invoice::class, 'create'])->name('app.invoice.add');
        Route::match(['get', 'post'], '/create/{uuid}', [Invoice::class, 'addParticulars'])->name('app.invoice.add.particulars');
        Route::match(['get', 'post'], '/edit/{uuid}', [Invoice::class, 'edit'])->name('app.invoice.edit');
        Route::get('/delete/{uuid}', [Invoice::class, 'delete'])->name('app.invoice.delete');
        Route::get("/generate/{uuid}", [Invoice::class, "invoicePdf"])->name('app.invoice.generate');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::match(['get', 'post'], '/details', [User::class, "details"])->name("app.user.details");
        Route::get('/list' , [User::class , "list"])->name("app.user.list");
    });
});
