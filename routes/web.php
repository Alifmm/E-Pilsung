<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\DashboardController;


// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/register', [AuthController::class, 'apiRegister']);
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:sanctum')->post('/apilogout', [AuthController::class, 'apiLogout']);

// Protected Routes
Route::middleware('auth')->group(function () {

    Route::get('/user/confirmpassword', [AuthController::class, 'showConfirmPassword'])->name('confirmpassword');
    Route::post('/user/confirmpassword', [AuthController::class, 'confirmPassword']);
    Route::get('/user/vote', [VoteController::class, 'showVotePage'])->name('vote.page');
    Route::post('/user/vote', [VoteController::class, 'vote'])->name('vote.submit');
    

    Route::get('/user/finishvote',[VoteController::class,'finishvote'])->name('user.finishvote');
    
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin');
    Route::post('/admin/toggle-showvote', [DashboardController::class, 'toggleShowVote'])->name('admin.toggleShowVote');

    // Calon Routes
    Route::get('/admin/manajemencalon', [CalonController::class, 'index'])->name('calons.index');
    Route::get('/admin/manajemencalon/create', [CalonController::class, 'create'])->name('calons.create');
    Route::post('/admin/manajemencalon', [CalonController::class, 'store'])->name('calons.store');
    Route::get('/admin/manajemencalon/{calon}/edit', [CalonController::class, 'editCalon'])->name('calons.edit');
    Route::put('/admin/manajemencalon/{calon}', [CalonController::class, 'updateCalon'])->name('calons.update');
    Route::delete('/admin/manajemencalon/{calon}', [CalonController::class, 'destroyCalon'])->name('calons.destroy');
    Route::get('/admin/manajemencalon/calondaerah/{calondaerah}/edit', [CalonController::class, 'editCalonDaerah'])->name('calonsdaerah.edit');
    Route::put('/admin/manajemencalon/calondaerah/{calondaerah}', [CalonController::class, 'updateCalonDaerah'])->name('calonsdaerah.update');
    Route::delete('/admin/manajemencalon/calondaerah/{calondaerah}', [CalonController::class, 'destroyCalonDaerah'])->name('calonsdaerah.destroy');

    // User Routes
    Route::get('/admin/manajemenuser', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/manajemenuser/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/manajemenuser', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/manajemenuser/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/manajemenuser/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/manajemenuser/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/{any}', function ($any) {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/' . $any);
        }
        return redirect('/')->with('error', 'Unauthorized access.');
    })->where('any', '.*');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin');
        }
        return redirect('/')->with('error', 'Unauthorized access.');
    });
});
