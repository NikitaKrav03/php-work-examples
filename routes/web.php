<?php

use App\Livewire\Pages\DataTablePage;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DataTablePage::class)
        ->middleware('role:user,manager')
        ->name('dashboard');

    Route::get('/admin/data', DataTablePage::class)
        ->middleware('role:admin')
        ->name('admin.data');

    Route::view('/profile', 'profile')
        ->name('profile');
});

require __DIR__.'/auth.php';
