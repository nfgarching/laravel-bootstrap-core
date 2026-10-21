<?php

namespace Nfgarching\Core\Controllers;

use Illuminate\Support\Facades\Route;
use Nfgarching\Core\Livewire\Raw;

Route::get('/greeting_from_bootstrap', function () {
    return response('Gruß vom Package Bootstrap');
});

Route::middleware('web')->group(function () {

    Route::get('testform', function () {
        return view('page::testform');
    })->name('testform');

    Route::get('testing', function () {
        return view('page::testing');
    })->name('testing');

    Route::get('testpage', function () {
        return view('page::testpage');
    })->name('testpage');

});

Route::middleware(['web', 'auth'])->group(function () {

    Route::get('admin', function () {
        return view('page::admin');
    })->name('admin');

    if (file_exists(resource_path('views/dashboard.blade.php'))) {
        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    } else {
        Route::get('dashboard', function () {
            return view('page::dashboard');
        })->name('dashboard');
    }

    if (file_exists(resource_path('views/startpage.blade.php'))) {
        Route::get('startpage', function () {
            return view('startpage');
        })->name('startpage');
    } else {
        Route::get('startpage', function () {
            return view('page::startpage');
        })->name('startpage');
    }
});

Route::middleware('web', 'guest')->group(function () {

    Route::get('guestpage', function () {
        return view('page::guestpage');
    })->name('guestpage');

});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/raw', Raw::class)->name('raw');

});

Route::middleware(['web', 'auth'])
    ->name('core.')->group(function () {
        Route::resource('props', PropController::class);
        Route::get('/props/{id}/action', [PropController::class, 'action'])->name('props.action');
    });

require __DIR__.'/settings.php';
