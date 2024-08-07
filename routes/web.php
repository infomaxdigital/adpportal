<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\MastersController;
use App\Http\Controllers\MydancestyleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnquiryController;
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
Route::post('master-discount-chnagestatus', [MastersController::class, 'discountchangestatus'])->name('master-discount-chnagestatus');
});

Route::get('master-membership',[MastersController::class,'membership'])->name('master-membership');
Route::post('master-membership-create',[MastersController::class,'membershipcreate'])->name('master-membership-create');
Route::post('master-membership-update',[MastersController::class,'membershipupdate'])->name('master-membership-update');
Route::post('master-membership-chnagestatus', [MastersController::class, 'membershipchangestatus'])->name('master-membership-chnagestatus');


//Enquiries urls
Route::get('enquiries',[EnquiryController::class,'enquiries'])->name('enquiries');
Route::get('student-create', [EnquiryController::class, 'create'])->name('student-create');


// Availabilities urls
Route::get('classes',[ClassController::class,'classes'])->name('classes');
Route::get('class-create',[ClassController::class,'create'])->name('class-create');
Route::post('class-store',[ClassController::class,'store'])->name('class-store');
Route::get('class-delete/{id}',[ClassController::class,'destroy'])->name('class-delete');

// My dance style urls

Route::get('my-dance-style',[MydancestyleController::class,'index'])->name('my-dance-style');
Route::post('my-dance-style-store',[MydancestyleController::class,'store'])->name('my-dance-style-store');

// Group class urls
Route::get('group-classes',[ClassController::class,'groupclasses'])->name('group-classes');
Route::get('group-class-create',[ClassController::class,'groupclasscreate'])->name('group-class-create');
Route::get('group-class-view/{id}', [ClassController::class, 'groupclassview'])->name('group-class-view');

// User Profile urls

Route::get('profile',[ProfileController::class,'index'])->name('profile');
Route::post('my-profile-store/{id}',[ProfileController::class,'store'])->name('my-profile-store');
