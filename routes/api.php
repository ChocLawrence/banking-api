<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\EmailVerificationController;

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
Route::middleware(['cors'])->group(function () {


  Route::get('/', [UserController::class,'root'])->name('root');

  //Authentication
  Route::post('login', [UserController::class,'login']);
  Route::post('signup', [UserController::class,'signup']);

  Route::post('forgot-password', [UserController::class, 'forgotPassword']);
  Route::post('reset-password', [UserController::class, 'reset']);


  //Account Types : public information
  Route::get('accounttypes',[AccountTypeController::class, 'getAccountTypes']); 
  Route::get('accounttypes/{id}',[AccountTypeController::class, 'getAccountType']);

  //Protected routes
  Route::middleware(['auth:sanctum'])->group(function () {   

    Route::post('logout', [UserController::class, 'logout']);

    //user updates
    Route::post('users/update',[UserController::class,'updateUser']);
    Route::get('users/{id}',[UserController::class, 'getUser']);

   
    //admin
    Route::middleware('role:admin')->group(function () {   

     
      //Account types : admin 
      Route::post('accounttypes',[AccountTypeController::class,'addAccountType']);
      Route::put('accounttypes/{id}',[AccountTypeController::class,'updateAccountType']);
      Route::delete('accounttypes/{id}',[AccountTypeController::class,'deleteAccountType']);


      //Roles
      Route::get('roles',[RoleController::class, 'getRoles']); 
      Route::get('roles/{id}',[RoleController::class, 'getRole']);
      Route::post('roles',[RoleController::class,'addRole']);
      Route::put('roles/{id}',[RoleController::class,'updateRole']);
      Route::delete('roles/{id}',[RoleController::class,'deleteRole']);


      //users
      Route::get('users',[UserController::class, 'getUsers']);
      Route::delete('users/{id}',[UserController::class,'deleteUser']);


    });

    //admin,employee
    Route::middleware('role:employee')->group(function () {   

      //Account
      Route::get('accounts',[AccountController::class, 'getAccounts']); 
      Route::get('accounts/{id}',[AccountController::class, 'getAccount']);
      Route::get('accounts/balance/{id}',[AccountController::class, 'getAccountBalance']);
      Route::post('accounts',[AccountController::class,'addAccount']);
      Route::put('accounts/{id}',[AccountController::class,'updateAccount']);
      Route::delete('accounts/{id}',[AccountController::class,'deleteAccount']);


      //Transaction
      Route::get('transactions',[TransactionController::class, 'getTransactions']); 
      Route::get('transactions/{id}',[TransactionController::class, 'getTransaction']);
      Route::post('transactions',[TransactionController::class,'addTransaction']);
      Route::put('transactions/{id}',[TransactionController::class,'getTransaction']);

    });


    //customer
    Route::middleware('role:customer')->group(function () {   

      Route::get('accounts/{id}',[AccountController::class, 'getAccount']);
      Route::get('accounts/balance/{id}',[AccountController::class, 'getAccountBalance']);
      Route::post('accounts',[AccountController::class,'addAccount']);
      Route::put('accounts/{id}',[AccountController::class,'updateAccount']);

      //users
      Route::get('users/{id}',[UserController::class, 'getUser']);
      Route::post('users/update',[UserController::class,'updateUser']);


      //Transaction
      Route::get('transactions/{id}',[TransactionController::class, 'getTransaction']);
      Route::post('transactions',[TransactionController::class,'addTransaction']);
      Route::put('transactions/{id}',[TransactionController::class,'getTransaction']);

    });


  
  });

});
 

