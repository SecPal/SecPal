<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Journal;
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

Route::get('login', Login::class)
    ->name('login')
    ->middleware('guest');

Route::get('home', Dashboard::class)
    ->name('dashboard')
    ->middleware('auth');

Route::get('journal', Journal::class)
    ->name('journal')
    ->middleware('auth');

Route::get('/', function () {
    return redirect('/home');
});
