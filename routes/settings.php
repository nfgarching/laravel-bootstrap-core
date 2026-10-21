<?php

namespace Nfgarching\Core\Controllers\Auth;

use Illuminate\Support\Facades\Route;

// use Laravel\Fortify\Features;

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Route::livewire('settings/profile', Profile::class)->name('profile.edit');
// });

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::livewire('settings/password', Password::class)->name('user-password.edit');
//     Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

//     Route::livewire('settings/two-factor', TwoFactor::class)
//         ->middleware(
//             when(
//                 Features::canManageTwoFactorAuthentication()
//                 && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
//                 ['password.confirm'],
//                 [],
//             ),
//         )
//         ->name('two-factor.show');
// });

Route::middleware(['web', 'auth'])->group(function () {

    Route::match(['get', 'post'], 'logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::resource('settings', UserController::class)->only(['edit', 'update']);

    // Route::get('/settings', [UserController::class, 'edit'])->name('user.settings');
    // Route::patch('/settings', [UserController::class, 'update'])->name('user.settings.update');

});

Route::middleware('web', 'guest')->group(function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', [UserController::class, 'create'])
        ->name('register');
    Route::post('register', [UserController::class, 'store']);

});
