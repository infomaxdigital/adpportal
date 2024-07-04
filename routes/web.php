<?php

use App\Http\Controllers\MastersController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    if(!Auth::check()) 
    {
        return view('auth.login');
    }
    else
    {
        return redirect('home');
    }
});

Auth::routes(['verify' => true, 'register' => false]);


Route::group(['middleware' => ['auth']], function() {

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Roles urls
Route::get('roles', [RoleController::class, 'index'])->name('roles');
Route::get('role-create', [RoleController::class, 'create'])->name('role-create');
Route::post('createrole', [RoleController::class, 'store'])->name('createrole');
Route::get('roleedit/{id}', [RoleController::class, 'edit'])->name('roleedit');
Route::post('updaterole/{id}', [RoleController::class, 'update'])->name('updaterole');
Route::get('deleterole/{id}', [RoleController::class, 'destroy'])->name('deleterole');

// Permission urls
Route::get('permissions', [PermissionController::class, 'index'])->name('permissions');
Route::get('permission-create', [PermissionController::class, 'create'])->name('permission-create');
Route::post('createpermission', [PermissionController::class, 'store'])->name('createpermission');
Route::get('permissionedit/{id}', [PermissionController::class, 'edit'])->name('permissionedit');
Route::post('updatepermission/{id}', [PermissionController::class, 'update'])->name('updatepermission');
Route::get('deletepermission/{id}', [PermissionController::class, 'destroy'])->name('deletepermission');


Route::get('givepermissions/{id}', [RoleController::class, 'addPermissionToRole'])->name('givepermissions');
Route::post('givepermissionstorole/{id}', [RoleController::class, 'givePermissionToRole'])->name('givepermissionstorole');


// Users urls
Route::get('users', [UserController::class, 'index'])->name('users');
Route::get('users-create', [UserController::class, 'create'])->name('users-create');
Route::post('createuser', [UserController::class, 'store'])->name('createuser');
Route::get('useredit/{id}', [UserController::class, 'edit'])->name('useredit');
Route::post('updateuser/{id}', [UserController::class, 'update'])->name('updateuser');
Route::get('deleteuser/{id}', [UserController::class, 'destroy'])->name('deleterole');

//Masters urls

Route::get('master-dance-style',[MastersController::class,'danceStyle'])->name('master-dance-style');
Route::post('master-dancestyle-create', [MastersController::class, 'dancestylecreate'])->name('master-dancestyle-create');
Route::post('master-dancestyle-update', [MastersController::class, 'dancestyleupdate'])->name('master-dancestyle-update');
Route::post('master-dancestyle-chnagestatus', [MastersController::class, 'dancestylchangestatus'])->name('master-dancestyle-chnagestatus');

Route::get('master-dance-level',[MastersController::class,'danceLevel'])->name('master-dance-level');
Route::post('master-dancelevel-create', [MastersController::class, 'dancelevelcreate'])->name('master-dancelevel-create');
Route::post('master-dancelevel-update', [MastersController::class, 'dancelevelupdate'])->name('master-dancelevel-update');
Route::post('master-dancelevel-chnagestatus', [MastersController::class, 'dancelevelchangestatus'])->name('master-dancelevel-chnagestatus');

Route::get('master-discount',[MastersController::class,'discount'])->name('master-discount');
Route::post('master-discount-create',[MastersController::class,'discountcreate'])->name('master-discount-create');
Route::post('master-discount-update',[MastersController::class,'discountupdate'])->name('master-discount-update');
});
